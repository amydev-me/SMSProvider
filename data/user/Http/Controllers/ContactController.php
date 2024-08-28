<?php

namespace User\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Group;
use App\Models\Sender;
use App\Models\Contact;
use App\Models\Country;

use App\Models\LogDetail;

use Carbon\Carbon;
use Validator;
use Auth;

class ContactController extends Controller
{
	public function showContactList()
	{
		return view('contacts.contacts-list');
	}

	public function create(Request $request)
	{
		$validator = $this->contactCreateValidator($request->all());

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check_mobile = Contact::where('mobile', $request->mobile)->where('user_id', Auth::guard('web')->user()->id)->first();
		if ($check_mobile) {
			return response()->json(['status' => false, 'message' => ['mobile' => 'The mobile number is already existed.']], 200);
		}

		$request['user_id'] = Auth::guard('web')->user()->id;

		try {
			$contact = Contact::create($request->except('groups'));
			$contact->groups()->attach($request->groups);
			return response()->json(['status' => true], 200);
		} catch (\Exception $e) {
			return response()->json(['status' => false], 500);
		}
	}

	private function contactCreateValidator(array $data){
		return Validator::make($data, [
			'contactName' => 'required',
			'mobile' => 'required',
			'groups' => 'required',
			'email' => 'max:50',
			'work' => 'max:50',
			'companyName' => 'max:50',
		]);
	}

	public function editContact(Request $request)
	{
		$validator = $this->contactEditValidator($request->all());

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check_mobile = Contact::where('mobile', $request->mobile)->where('user_id', Auth::guard('web')->user()->id)->where('id', '<>', $request->id)->first();
		if ($check_mobile) {
			return response()->json(['status' => false, 'message' => ['mobile' => 'The mobile number is already existed.']], 200);
		}

		$input = $request->except('groups', 'created_at', 'updated_at');

		$contact = Contact::CurrentUser()->FindById($input['id'])->first();

		if ($contact) {
			if($request->birthdate) {
				$input['birthdate'] = Carbon::parse($request->birthdate)->format('Y-m-d');
			}

			$contact->update($input);
			$contact->groups()->detach();
			$contact->groups()->attach($request->groups);
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => 'Invalid Request.'], 403);
	}

	private function contactEditValidator(array $data){
		return Validator::make($data, [
			'id' => 'required',
			'contactName' => 'required',
			'mobile' => 'required',
			'groups' => 'required',
			'email' => 'max:50',
			'work' => 'max:50',
			'companyName' => 'max:50',
		]);
	}

	public function deleteContact($id)
	{
		$contact = Contact::FindById($id)->first();
		if ($contact) {
			$contact->delete();
			return response()->json(['status' => true]);
		}
		return response()->json(['status' => false]);
	}

	public function getContactDetail($id)
	{
		$contact_id = $id;
		$contact = Contact::CurrentUser()->FindById($contact_id)->first();
		$groups = $contact->groups()->select('id', 'groupName')->get();

		if ($contact) {
			return response()->json(['status' => true, 'contact' => $contact, 'groups' => $groups->makeHidden('contact_count')], 200);
		}
		return response()->json(['status' => false, 'contact' => null], 200);
	}

	public function viewContact(Request $request)
	{
		$contact_id = $request->contact_id;
		$contact = Contact::currentUser()->FindById($contact_id)->with('groups')->first();
		if ($contact) {
			return view('contacts.contact-detail');
		}
		return redirect('/list');
	}

	//TODO::Take 10 Contacts+Groups
	public function async_contacts(Request $request)
	{
		$groups = Group::currentUser()
						->where('groupName', 'LIKE', '%' . $request->param . '%')
						->select(['id', 'groupName AS value'])
						->get();

		$groups->map(function ($item) {
			$item['entityId'] = $item->id;
			$item['type'] = 'list';
			$item['label'] = $item->contact_count;
			return $item;
		})->makeHidden(['id', 'contact_count']);

		$contacts = Contact::where('user_id', Auth::guard('web')->user()->id)
							->where(function ($query) use ($request) {
								$query->where('contactName', 'LIKE', '%' . $request->param . '%')
									->orwhere('mobile', '=', $request->param)
									->orWhere('mobile', 'LIKE', $request->param . '%')
									->orWhere('mobile', 'LIKE', $request->param . '%')
									->orWhere('mobile', 'LIKE', '%' . $request->param . '%')
									->orWhere('mobile', 'LIKE', '%' . $request->param);
							})
							->select(['id AS entityId', 'mobile AS label' , 'contactName AS value'])
							->take(5)
							->get();

		$contacts->map(function ($item) {
			$item['type'] = 'contact';
			return $item;
		});

		$log_detail = LogDetail::whereHas('sms_log', function ($query) {
									$query->where('user_id', Auth::guard('web')->user()->id);
								})
								->where(function ($query) use ($request) {
									$query->where('recipient', 'LIKE', '%' . $request->param . '%');
								})
								->groupBy('recipient')
								->select(['recipient AS value'])
								->take(5)
								->get();

		$log_detail->map(function ($item) {
			$item['entityId'] = NULL;
			$item['type'] = 'number';
			$item['label'] = NULL;
			return $item;
		});

		return array_merge($groups->all(), $contacts->all(), $log_detail->all());
	}


	public function getAllContacts()
	{
		$contacts= Contact::where('user_id',Auth::guard('web')->user()->id)->orderBy('contactName')->paginate(10);
		return response()->json($contacts);
	}

	public function filterContactByPhoneAndName(Request $request)
	{
		$contacts= Contact::currentUser()->where('contactName','LIKE',$request->param.'%')->orWhere('mobile','LIKE','%'.$request->param.'%')->paginate(5);
		return response()->json($contacts);
	}

	public function getCountries(Request $request)
	{
		$countries = Country::where('status', '1')
							->where('rate', '>', '0')
							->get();

		return response()->json($countries);
	}

	public function getSenders(Request $request)
	{
		$senders = Sender::whereHas('sender_users', function ($query) {
							$query->where('user_id', Auth::guard('web')->user()->id);
						})
						->orderBy('sender_name')
						->pluck('sender_name');

		return response()->json($senders);
	}
}
