<?php

namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\SendSmsWithAdminToken;
use Yajra\Datatables\Datatables;

use App\Models\IntlOrderNotification;
use App\Models\OrderNotification;

use App\Models\UserBalance;

use App\Models\UserPackage;
use App\Models\PaygInvoice;

use App\Models\Package;
use App\Models\User;

use Carbon\Carbon;
use Validator;
use Mail;
use PDF;
use DB;

class UserPackageController extends Controller
{
	use SendSmsWithAdminToken;

	public function index($package_id = NULL)
	{
		if ($package_id != NULL && $package_id != '') {
			self::markAsRead($package_id);
		} else {
			self::markAsRead();
		}

		return view('admin-views.order', ['package_id' => $package_id]);
	}

	public function getOrders(Request $request)
	{
		$orders = UserPackage::with('user', 'package')
								->whereHas('package', function($query) {
									$query->notFree();
								});

		if ($request->has('user_id') && $request->get('user_id') != '') {
			$user_id = $request->user_id;

			$sms_type = User::where('id', $user_id)->value('sms_type');

			if ($sms_type == 'PAYG') {
				$orders = PaygInvoice::with('user');
			}

			$orders = $orders->whereHas('user', function($query) use ($user_id) {
								$query->where('id', $user_id);
							});
		}

		return Datatables::of($orders)
						->editColumn('user.username', function ($row) {
							return '<a href="/admin/user/view/' . $row->user->id . '" target="_blank">' . $row->user->username . '</a>';
						})
						->editColumn('cost', function ($row) {
							return number_format($row->cost);
						})
						->editColumn('credit', function ($row) {
							return number_format($row->credit);
						})
						->editColumn('extra_credit', function ($row) {
							return number_format($row->extra_credit);
						})
						->editColumn('total_credit', function ($row) {
							return number_format($row->total_credit);
						})
						->editColumn('payment_date', function ($row) {
							if ($row->payment_date != NULL) {
								return Carbon::parse($row->payment_date)->timezone('Asia/Yangon')->format('d M Y');
							}
						})
						->editColumn('order_date', function ($row) {
							return Carbon::parse($row->order_date)->timezone('Asia/Yangon')->format('d M Y');
						})
						->editColumn('invoice_date', function ($row) {
							return Carbon::parse($row->invoice_date)->timezone('Asia/Yangon')->format('d M Y');
						})
						->editColumn('status', function ($row) {
							return ucfirst($row->status);
						})
						->addColumn('action', function ($row) {
							$confirm_btn = '<a href="javascript:void(0)" class="confirm_order" data-id="' . $row->id . '" title="Confirm Order"><i class="fas fa-check text-success"></i></a> | ';
							$payment_btn = '<a href="javascript:void(0)" class="change_payment" data-id="' . $row->id . '" title="Receive Payment"><i class="fas fa-hand-holding-usd"></i></a> | ';
							$cancel_btn = '<a href="javascript:void(0)" class="cancel_order" data-id="' . $row->id . '" title="Cancel Order"><i class="far fa-times-circle text-danger"></i></a>';

							$column = '';

							if ($row->user->obsolete == '0') {
								if ($row->status != 'cancel') {
									if ($row->status == 'pending') {
										$column .= $confirm_btn . '' . $payment_btn . '' . $cancel_btn;
									} elseif ($row->status == 'confirm') {
										$column .= $payment_btn . '' . $cancel_btn;
									}
								}
							}

							return $column;
						})
						->rawColumns([ 'user.username', 'action' ])
						->filter(function ($query) use ($request) {
							if ($request->has('status') && $request->get('status') != '') {
								$query->where('status', $request->status);
							}

							if ($request->has('package_id') && $request->get('package_id') != '') {
								$query->where('package_id', $request->package_id);
							}

							if ($request->has('order_date') && $request->get('order_date') != '') {
								$query->whereDate('order_date', Carbon::parse($request->order_date)->format('Y-m-d') );
							}

							// if ($request->has('user_id') && $request->get('user_id') != '') {
							// 	$query->where('user_id', $request->user_id);
							// }
						}, TRUE)
						->toJson();
	}

	public function updateOrder(Request $request)
	{
		$order = UserPackage::where('id', $request->order_id)->first();

		if ($order) {
			$order->update(['status' => $request->status]);

			if ($request->status == 'paid') {
				UserBalance::where('user_id', $order->user_id)->increment('balance', $order->total_credit);

				$order->user()->update(['account_type' => 'Premium']);
				$order->update(['payment_date' => Carbon::now()]);
			}

			$this->sendOrderEmail($request->all(), $order->user->sms_type);

			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

	private function sendOrderEmail($request, $sms_type)
	{
		$invoice = UserPackage::where('id', $request['order_id'])->with('user', 'package')->first();

		$email = User::where('id', $invoice->user_id)->value('email');

		if ($request['status'] == 'confirm') {
			if ($sms_type == 'Package') {
				$subject = 'Thank you for using our TripleSMS gateway service. Your order of ' . $invoice->package->packageName . ' package has been confirmed.';
			} else {
				$subject = 'Thank you for using our TripleSMS gateway service. Your order of ' . $invoice->total_usd . ' USD has been confirmed.';
			}

			$template = 'admin-mail.bank-mail';

			Mail::send($template, compact('invoice'), function($message) use ($email, $subject) {
				$message->to($email)
						->subject($subject);
			});

			$this->sendSmsWithAdminToken($invoice->user->mobile, $subject . ' Please check your email for more details.');
		} elseif ($request['status'] == 'paid') {
			$subject = 'Thank you for your purchase and your payment is received.';
			$template = 'admin-mail.blank';

			$filepath = storage_path() . '/app/tmp/';
			$filename = $filepath . $invoice->invoice_no . '.pdf';
			PDF::loadView('admin-mail.invoice', compact('invoice'))->setPaper('a5')->save($filename);

			Mail::send($template, compact('invoice'), function($message) use ($email, $subject, $filename) {
				$message->to($email)
						->subject($subject)
						->attach($filename);
			});

			unlink($filename);

			$this->sendSmsWithAdminToken($invoice->user->mobile, ' Your payment has been received and ' . $invoice->total_credit . ' credits have been added to your account. Please check your email for invoice.');
		}
	}

	public function getUsers(Request $request)
	{
		$users = User::select(['id', 'username', 'sms_type'])
					->where('username', 'LIKE', '%' . $request->name . '%')
					// ->where('sms_type', 'Package')
					->where('obsolete', '0')
					->get();
		return response()->json($users);
	}

	public function getPackages(Request $request)
	{
		$packages = Package::select(['id', 'packageName'])
						->where('packageName', 'LIKE', '%' . $request->name . '%')
						->where('active', '1')
						->get();
		return response()->json($packages);
	}

	public function create(Request $request)
	{
		$rules = [
				'user_id' => 'required',
				'status' => 'required'
			];

		if ($request->has('sms_type') && $request->get('sms_type') != '') {
			if ($request->sms_type == 'Package') {
				$rules['package_id'] = 'required';
			} elseif ($request->sms_type == 'USD') {
				$rules['total_usd'] = 'required|numeric';
			}
		}

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$params = [
				'user_id' => $request->user_id,
				'invoice_no' => Carbon::now()->timestamp,
				'payment_method' => 'Bank',
				'status' => $request->status,
				'order_date' => Carbon::now()
			];

		if ($request->sms_type == 'Package') {
			$package = Package::with(['promotions' => function($query) {
									$query->where('active', '1');
								}])
								->where('id', $request->package_id)
								->first();

			$params['package_id'] = $request->package_id;
			$params['cost'] = $package->cost;
			$params['credit'] = $package->credit;
			$params['total_credit'] = $package->credit;

			if (count($package->promotions) == 1) {
				$remaining_promo = $this->getRemainingPromo($package, $request->user_id);

				if ($remaining_promo > 0) {
					$params['promotion_id'] = $package->promotions{0}->id;
					$params['extra_credit'] = $package->promotions{0}->promo_credit;
					$params['total_credit'] = $package->credit + $package->promotions{0}->promo_credit;
				}
			}
		} else {
			$params['total_usd'] = $request->total_usd;
		}

		if ($request->status == 'paid') {
			$params['payment_date'] = Carbon::now();
		}

		$order = UserPackage::create($params);

		if ($order) {
			UserBalance::where('user_id', $request->user_id)->increment('balance', $order->total_credit);

			$mail_param = [
					'order_id' => $order->id,
					'status' => $request->status
				];

			self::sendOrderEmail($mail_param, $request->sms_type);
		}

		if ($request->status == 'paid') {
			$order->user()->update(['account_type' => 'Premium']);
		}

		return response()->json(['status' => true, 'order' => $order], 200);
	}

	public function getNotifications()
	{
		$notifications = OrderNotification::where('read', '0')
										->select('package_id', DB::raw('count(*) AS total_packages'))
										->groupBy('package_id')
										->get();

		if ($notifications) {
			$response['status'] = true;
			$response['notification_count'] = $notifications->sum('total_packages');

			$packages = [];
			foreach ($notifications as $key) {
				$packages[ strtolower($key->package_name) ] = [
					'package_id' => $key->package_id,
					'total_packages' => $key->total_packages
				];
			}

			$response['packages'] = $packages;

			return response()->json($response, 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

	public function markAsRead($package_id = NULL)
	{
		if ($package_id != NULL && $package_id != '') {
			OrderNotification::where('package_id', $package_id)->update(['read' => '1', 'read_at' => Carbon::now()]);
		} else {
			OrderNotification::where('read', '0')->update(['read' => '1', 'read_at' => Carbon::now()]);
		}
	}

	private function getRemainingPromo($package, $user_id)
	{
		$user_package = UserPackage::where('promotion_id', $package->promotions{0}->id)
									->where('user_id', $user_id)
									->where('status', '<>', 'cancel');
		$used_promo = $user_package->count('promotion_id');

		$remaining_promo = $package->promotions{0}->max_purchase - $used_promo;
		return $remaining_promo;
	}
}
