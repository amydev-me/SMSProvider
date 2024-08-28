<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OperatorDetail;
use App\Models\Operator;
use App\Models\Country;
use Validator;

class OperatorController extends Controller
{
	public function viewOperators(Request $request)
	{
		$country = Country::where('id', $request->country_id)->first();

		if (!$country) {
			return redirect()->back();
		}

		return view('admin-views.operator', compact('country'));
	}

	public function getOperators($country_id)
	{
		$operators = Operator::with('operator_detail')->where('country_id', $country_id)->get();
		return response()->json($operators);
	}

	public function search($name)
	{
		$operators = Operator::with('operator_detail')
							->where('name', 'LIKE', '%' . $name . '%')
							->orWhere('rate', 'LIKE', '%' . $name . '%')
							->get();
		return response()->json($operators);
	}

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
				'name' => 'required',
				'country_id' => 'required'
			]
		);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$operator = Operator::create($request->all());

		if ($operator) {
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
				'name' => 'required',
				'country_id' => 'required'
			]
		);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$operator = Operator::where('id', $request->id)->first();
		if ($operator) {
			$operator->update($request->except('id'));

			return response()->json(['success' => true]);
		}
		
		return response()->json(['success' => false]);
	}

	public function delete($id)
	{
		$operator = Operator::where('id', $id)->first();
		if ($operator) {
			$operator->delete();
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function getNumbers($id)
	{
		$numbers = OperatorDetail::where('operator_id', $id)->get();
		return $numbers;
	}

	public function createNumber(Request $request)
	{
		$validator = Validator::make($request->all(), [
				'country_id' => 'required',
				'operator_id' => 'required',
				'starting_number' => 'required|integer'
			]
		);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$prefix = Country::where('id', $request->country_id)->value('prefix');
		$request['starting_number'] = $prefix . '' . $request->starting_number;

		$check = OperatorDetail::where('starting_number', $request['starting_number'])->first();
		if ($check) {
			$operator_exists = Operator::with('country')->where('id', $check->operator_id)->first();
			$message = 'This number is already existed in ' . $operator_exists->name . ', ' . $operator_exists->country->name;

			return response()->json(['status' => false, 'message' => ['check' => $message]], 200);
		}

		$operator = OperatorDetail::create($request->all());

		if ($operator) {
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function deleteNumber($id)
	{
		$operator_detail = OperatorDetail::where('id', $id)->first();
		if ($operator_detail) {
			$operator_detail->delete();
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}

	public function getMyanmarOperators()
	{
		$operators = Operator::select(['id', 'name'])
							->where('country_id', 152)
							->get();
		return response()->json($operators);
	}

    public function asyncOperators($country_id)
    {
        $operators = Operator::select(['id', 'name'])
            ->where('country_id', $country_id)
            ->get();
        return response()->json($operators);
    }
}