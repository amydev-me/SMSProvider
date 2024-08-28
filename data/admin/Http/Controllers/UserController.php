<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Validation\Rule;

use App\Models\UserBalance;

use App\Models\UserPackage;
use App\Models\UserSetting;
use App\Models\PaygInvoice;
use App\Models\UserCredit;
use App\Models\UserRate;
use App\Models\Package;
use App\Models\SmsLog;
use App\Models\Group;
use App\Models\User;

use Carbon\Carbon;
use Validator;
use Hash;
use Mail;

use Yajra\Datatables\Datatables;

class UserController extends Controller
{
	public function index($user = NULL)
	{
		return view('admin-views.user', ['user' => $user]);
	}

	public function getUsers(Request $request)
	{
		$users = User::query();

		return Datatables::of($users)
						->addColumn('remaining_credit', function ($row) {
							if ($row->sms_type == 'Package') {
								return $this->credits($row->id);
							} elseif ($row->sms_type == 'USD') {
								return $this->usd_credits($row->id) . ' USD';
							}
						})
						->addColumn('unpaid_credit', function($row) {
							if ($row->sms_type == 'PAYG') {
								return $this->unpaid_credits($row->id);
							}
						})
						->addColumn('action', function ($row) {
							if ($row->block == '1') {
								$block_icon = '<i class="fas fa-check text-success"></i>';
								$block_text = 'Unblock User';
							} else {
								$block_icon = '<i class="fas fa-ban text-warning"></i>';
								$block_text = 'Block User';
							}

							$view_btn = '<a href="/admin/user/view/' . $row->id . '" class="view_user" title="View User"><i class="far fa-eye text-info"></i></a> | ';
							$edit_btn = '<a href="javascript:void(0)" class="edit_user" data-id="' . $row->id . '" title="Edit User"><i class="fas fa-edit text-primary"></i></a> | ';
							$block_btn = '<a href="javascript:void(0)" class="block_user" data-id="' . $row->id . '" data-username="' . $row->username . '" data-block="' . $row->block . '" title="' . $block_text . '">' . $block_icon . '</a> | ';
							$delete_btn = '<a href="javascript:void(0)" class="delete_user" data-id="' . $row->id . '" data-username="' . $row->username . '" title="Delete User"><i class="far fa-trash-alt text-danger"></i></a>';

							$column = $view_btn . '' . $edit_btn . '' . $block_btn . '' . $delete_btn;

							return $column;
						})
						->rawColumns([ 'action' ])
						->filter(function ($query) use ($request) {
							if ($request->has('account_type') && $request->get('account_type') != '') {
								$query->where('account_type', $request->get('account_type'));
							}
						}, TRUE)
						->toJson();
	}

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'username' => [
					'required',
					'max:30',
					Rule::unique('users')->where('obsolete', '0')
				],
			'full_name' => 'required',
			'email' => [
					'required',
					'email',
					Rule::unique('users')->where('obsolete', '0')
				],
			'mobile' => 'required',
			'password' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$user = self::insert($request->all());

		if ($user) {
			UserSetting::create([
				'user_id' => $user->id
			]);

			$group_names = ['Customers', 'Partners', 'Team#1', 'Team#2'];

			foreach($group_names as $group_name) {
				Group::create([
					'user_id' => $user->id,
					'groupName' => $group_name
				]);
			}

			$package = Package::GetFree();
			if ($package) {
				UserPackage::create([
					'user_id' => $user->id,
					'package_id' => $package->id,
					'credit' => $package->credit,
					'total_credit' => $package->credit,
					'cost' => $package->cost,
					'payment_method'=>'Bank',
					'status'=>'paid',
					'order_date' => Carbon::now()
				]);
			}
		}

		return response()->json(['status' => true, 'user' => $user], 200);
	}

	public static function insert($params)
	{
		return User::create([
			'username' => $params['username'],
			'email' => $params['email'],
			'mobile' => $params['mobile'],
			'password' => Hash::make($params['password']),
			'full_name' => $params['full_name'],
			'company' => $params['company'],
			'account_type' => 'Free',
			'verified' => '1',
			'address' => $params['address'],
			'accept_terms' => '1',
			'sms_type' => $params['sms_type']
		]);
	}

	public function edit($id)
	{
		$user = User::find($id);

		if ($user) {
			return response()->json(['status' => true, 'user' => $user], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'username' => 'required|max:30',
			'full_name' => 'required',
			'email' => 'required|email',
			'mobile' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check_username = User::where('username', $request->username)->where('id', '<>', $request->id)->where('obsolete', '0')->first();
		if ($check_username) {
			return response()->json(['status' => false, 'message' => ['username' => 'The username has already been taken.']], 200);
		}

		$check_email = User::where('email', $request->email)->where('id', '<>', $request->id)->where('obsolete', '0')->first();
		if ($check_email) {
			return response()->json(['status' => false, 'message' => ['email' => 'The email has already been taken.']], 200);
		}

		$user = User::where('id', $request->id)->first();

		if ($user) {
			$user->update($request->except('password'));
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

	public function delete(Request $request)
	{
		$user = User::find($request->id);

		if ($user) {
			$user->delete();
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

	public function block(Request $request)
	{
		$user = User::find($request->id);

		if ($user) {
			if ($user->block == 1) {
				$user->block = 0;
			} else {
				$user->block = 1;
			}

			$user->save();
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

	public function view($id)
	{
		$user = User::find($id);

		if ($user) {
			if ($user->sms_type == 'Package') {
				$credits = $this->credits($id);
			} elseif ($user->sms_type == 'PAYG') {
				$credits = $this->unpaid_credits($id);
			} else {
				$credits = $this->usd_credits($id);
			}
			
			return view('admin-views.user-detail', compact('user', 'id', 'credits'));
		}

		return back();
	}

	public function showOrders($id)
	{
		$user = User::find($id);

		if ($user) {
			return view('admin-views.user-order', compact('user', 'id'));
		}

		return back();
	}

	public function getUsdRate(Request $request)
	{
		$usd_rate = User::where('id', $request->id)->value('usd_rate');
		return response()->json($usd_rate);
	}

	public function updateUsd(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'usd_rate' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$user = User::where('id', $request->id)->first();

		if ($user) {
			$user->update(['usd_rate' => $request->usd_rate]);
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

	public function changePassword(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'required',
			'new_password' => 'required',
			'confirm_password' => 'required|same:new_password'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$user = User::where('id', $request->user_id)->first();

		if ($user) {
			$params = $request->all();
			$params['password'] = Hash::make($params['new_password']);
			$user->update($params);

			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

	public function credits($user_id)
	{
		$balance = UserBalance::where('user_id', $user_id)->first();

		if ($balance) {
			return $balance->balance;
		}

		$balance = UserPackage::where('user_id', $user_id)->where('status', 'paid')->sum('total_credit') - $this->usage($user_id);

		$new_balance = UserBalance::create(['user_id' => $user_id, 'balance' => $balance]);

		return UserBalance::where('user_id', $user_id)->value('balance');
	}

	public function usage($id)
	{
		return SmsLog::where('user_id', $id)->sum('total_credit');
	}

	public function unpaid_credits($id)
	{
		return $this->usage($id) - PaygInvoice::where('user_id', $id)->where('status', 'paid')->sum('total_credit');
	}

	public function usd_credits($id)
	{
		return UserPackage::where('user_id', $id)
							->where('status', 'paid')
							->sum('total_usd') - $this->usd_usage($id);
	}

	public function usd_usage($id)
	{
		return SmsLog::where('user_id', $id)->sum('total_sms') * User::where('id', $id)->value('usd_rate');
	}

	public function getUserRates(Request $request)
	{
		$user_rates = UserRate::with('user', 'country')->where('user_id', $request->id)->get();
		return $user_rates;
	}

	public function addUserRate(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'required',
			'country_id' => 'required',
			'rate' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = UserRate::where('user_id', $request->user_id)->where('country_id', $request->country_id)->first();

		if ($check) {
			return response()->json(['status' => false, 'message' => ['exist' => 'Country already existed.']], 200);
		}

		$user_rate = UserRate::create($request->all());

		if ($user_rate) {
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false], 200);
	}

	public function updateUserRate(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'required',
			'country_id' => 'required',
			'rate' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = UserRate::where('user_id', $request->user_id)->where('country_id', $request->country_id)->where('id', '<>', $request->id)->first();

		if ($check) {
			return response()->json(['status' => false, 'message' => ['exist' => 'Country already existed.']], 200);
		}

		$user_rate = UserRate::where('id', $request->id)->first();

		if ($user_rate) {
			$user_rate->update($request->except('id'));
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false], 200);
	}

	public function deleteUserRate($id)
	{
		$user_rate = UserRate::where('id', $id)->first();

		if ($user_rate) {
			$user_rate->delete();
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function addCredit(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'required',
			'credit' => 'required|numeric'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$params = [
				'user_id' => $request->user_id,
				'credit' => $request->credit,
				'total_credit' => $request->credit,
				'payment_method' => 'Bank',
				'status' => 'paid',
				'order_date' => Carbon::now()
			];

		$order = UserPackage::create($params);

		if ($order) {
			UserBalance::where('user_id', $request->user_id)->increment('balance', $request->credit);

			return response()->json(['status' => true], 200);
		}

		return response()->json(['success' => false]);
	}

	public function getUnpaidInvoice(Request $request)
	{
		return $this->unpaid_credits($request->user_id);
	}

	public function sendInvoice(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'user_id' => 'required',
			'unpaid_credit' => 'required|integer'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		if ($request->unpaid_credit > $this->getUnpaidInvoice($request)) {
			return response()->json(['status' => false, 'message' => ['exceed' => 'Invoice credit cannot exceed unpaid credit.']], 200);
		}

		$params = [
				'user_id' => $request->user_id,
				'invoice_no' => Carbon::now()->timestamp,
				'cost' => $request->unpaid_credit * 6,
				'credit' => $request->unpaid_credit,
				'total_credit' => $request->unpaid_credit,
				'payment_method' => 'Bank',
				'invoice_date' => Carbon::now(),
				'status' => 'pending',
			];

		$order = PaygInvoice::create($params);

		if ($order) {
			$this->sendPaygEmail($order);

			return response()->json(['status' => true], 200);
		}

		return response()->json(['success' => false]);
	}

	public function sendPaygEmail($invoice)
	{
		if ($invoice->status == 'pending') {
			$email = $invoice->user->email;
			$subject = 'Pay as you Go Invoice: ' . $invoice->invoice_no;
			$template = 'admin-mail.payg-blank';

			$filepath = storage_path() . '/app/tmp/';
			$filename = $filepath . $invoice->invoice_no . '.pdf';
			PDF::loadView('admin-mail.invoice', compact('invoice'))->setPaper('a5')->save($filename);

			Mail::send($template, compact('invoice'), function($message) use ($email, $subject, $filename) {
				$message->to($email)
						->subject($subject)
						->attach($filename);
			});

			unlink($filename);
		}
	}
}