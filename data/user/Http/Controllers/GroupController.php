<?php

namespace User\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\Group;

use Validator;
use Auth;

class GroupController extends Controller
{
	public function index()
	{
		return view('user.contacts.groups');
	}


	public function groupDetailView(Request $request)
	{
		$group = Group::currentUser()
						->FindById($request->group_id)
						->first();

		if (!$group) {
			return redirect('/address-book');
		}

		return view('contacts.group-detail');
	}

	public function getGroups()
	{
		$groups = Group::currentUser()
						->orderBy('groupName')
						->get();

		return response()->json($groups);
	}

	public function getGroupWithContacts($id)
	{
		$contacts = Group::with(['contacts' => function($query) {
							$query->orderBy('contactName');
						}])
						->currentUser()
						->FindById($id)
						->first();

		return response()->json($contacts);
	}

	public function asyncGroups()
	{
		$groups = Group::currentUser()
						->select(['id', 'groupName'])
						->get()
						->makeHidden('contact_count');

		return response()->json($groups);
	}

	public function getGroupOfContacts($group_id)
	{
		$groups = Group::currentUser()
						->where('id', $group_id)
						->first();

		if ($groups) {
			$contacts = $groups->contacts;
			return view('user.contacts.contacts', compact('contacts'));
		}

		return redirect()->back();
	}

	public function create(Request $request)
	{
		$request['user_id'] = Auth::guard('web')->user()->id;

		$validator = Validator::make($request->all(), [
			'user_id' => 'required',
			'groupName' => 'required|max:255'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 403);
		}

		$group = Group::create($request->all());

		return response()->json(['status' => true, 'group' => $group], 200);
	}

	private function validation(array $data){
		return Validator::make($data, [
			'id' => 'required',
			'groupName' => 'required|max:255'
		]);
	}

	public function update(Request $request)
	{
		$this->validation($request->all())->validate();

		$group = Group::currentUser()->FindById($request->id)->first();

		if ($group) {
			$group->update($request->all());
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Cann't  access data."], 403);

	}

	public function delete($id)
	{
		$group = Group::currentUser()->FindById($id)->first();

		if ($group) {
			$group->delete();
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Invalid request."], 403);
	}

	public function deleteContacts(Request $request)
	{
		if ($request->contacts) {
			Contact::currentUser()
					->whereIn('id', $request->contacts)
					->delete();

			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Cann't  access data."], 403);
	}
}