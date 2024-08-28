<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Operator;
use App\Models\Country;
use Validator;

class CountryController extends Controller
{
	public function getCountries()
	{
		$countries = Country::paginate(10);
		return response()->json($countries);
	}

	public function search($name)
	{
		$countries = Country::where('name', 'LIKE', '%' . $name . '%')
							->orWhere('iso', 'LIKE', '%' . $name . '%')
							->orWhere('code', 'LIKE', '%' . $name . '%')
							->orWhere('prefix', 'LIKE', '%' . $name . '%')
							->paginate(10);
		return response()->json($countries);
	}

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
				'name' => 'required|unique:countries',
				'iso' => 'required|unique:countries|alpha|size:3',
				'code' => 'required|unique:countries|alpha|size:2',
				'prefix' => 'required|unique:countries|integer|digits_between:1,3',
				'rate' => 'required|numeric',
				'cost' => 'required|numeric',
			]
		);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$country = Country::create($request->all());

		if ($country) {
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
				'name' => 'required',
				'iso' => 'required|alpha|size:3',
				'code' => 'required|alpha|size:2',
				'prefix' => 'required|integer|digits_between:1,3',
				'rate' => 'required|numeric',
				'cost' => 'required|numeric',
			]
		);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$err_check = [];

		$check_name = Country::where('name', $request->name)->where('id', '<>', $request->id)->first();
		if ($check_name) {
			$err_check['name'] = ['The name has already been taken.'];
		}

		$check_iso = Country::where('iso', $request->iso)->where('id', '<>', $request->id)->first();
		if ($check_iso) {
			$err_check['iso'] = ['The iso has already been taken.'];
		}

		$check_code = Country::where('code', $request->code)->where('id', '<>', $request->id)->first();
		if ($check_code) {
			$err_check['code'] = ['The code has already been taken.'];
		}

		$check_prefix = Country::where('prefix', $request->prefix)->where('id', '<>', $request->id)->first();
		if ($check_prefix) {
			$err_check['prefix'] = ['The prefix has already been taken.'];
		}

		if (count($err_check) > 0) {
			return response()->json(['status' => false, 'message' => $err_check], 200);
		}

		$country = Country::where('id', $request->id)->first();
		if ($country) {
			$country->update($request->except('id'));

			return response()->json(['success' => true]);
		}
		
		return response()->json(['success' => false]);
	}

	public function changeStatus($id)
	{
		$country = Country::where('id', $id)->first();
		if ($country) {
			if ($country->status == '1') {
				$country->update(['status' => '0']);
			} else {
				$country->update(['status' => '1']);
			}

			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function delete($id)
	{
		$country = Country::where('id', $id)->first();
		if ($country) {
			$check = Operator::where('country_id', $id)->first();
			if ($check) {
				return response()->json(['success' => false, 'message' => 'Cannot delete country with operators.']);
			}

			$country->delete();
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function getAsyncCountries(Request $request)
	{
		$countries = Country::where('status', '1')
							->where('rate', '>', '0')
							->get();

		return response()->json($countries);
	}

	public function getCountryForSelectBox(){
        $countries = Country::where('status', '1')->orderBy('name')

            ->select(['id','name'])
            ->get();

        return response()->json($countries);
    }
}