<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Promotion;
use App\Models\Package;

use Validator;

class PackageController extends Controller
{
	public function getPackages()
	{
		$packages = Package::with(['promotions' => function ($query) {
								$query->where('active', '1');
							}])
							->orderBy('cost')->get();
		return response()->json($packages);
	}

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'packageName' => 'required|unique:packages|max:100',
			'cost' => 'required|numeric',
			'currency_type' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$package = Package::create($request->all());

		if ($package) {
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false], 200);
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => 'required',
			'packageName' => 'required|max:100',
			'cost' => 'required|numeric',
			'currency_type' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = Package::where('packageName', $request->packageName)->where('id', '<>', $request->id)->first();

		if ($check) {
			return response()->json(['status' => false, 'message' => ['packageName' => 'The package name has already been taken.']], 200);
		}

		$package = Package::where('id', $request->id)->first();

		if ($package) {
			$package->update($request->all());
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false], 200);
	}

	public function createPromotion(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'package_id' => 'required',
			'promo_credit' => 'required|numeric',
			'max_purchase' => 'required|integer',
			'promo_status' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = Promotion::where('package_id', $request->package_id)->where('active', '1')->first();

		if ($check) {
			return response()->json(['status' => false, 'message' => ['promotion' => 'There is already a promotion for this package.']], 200);
		}

		$promotion = Promotion::create($request->all());

		if ($promotion) {
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false], 200);
	}

	public function updatePromotion(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'package_id' => 'required',
			'promo_credit' => 'required|numeric',
			'max_purchase' => 'required|integer',
			'promo_status' => 'required'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = Promotion::where('package_id', $request->package_id)->where('active', '1')->where('id', '<>', $request->id)->first();

		if ($check) {
			return response()->json(['status' => false, 'message' => ['promotion' => 'There is already a promotion for this package.']], 200);
		}

		$promotion = Promotion::where('id', $request->id)->first();

		if ($promotion) {
			$promotion->update($request->except('id'));
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false], 200);
	}

	public function deletePromotion($id)
	{
		$promotion = Promotion::where('id', $id)->first();

		if ($promotion) {
			$promotion->update(['active' => 0]);
			return response()->json(['success' => true]);
		}

		return response()->json(['success' => false]);
	}
}