<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CountryPrice;
use App\Models\Country;
use Carbon\Carbon;
use Validator;

use Yajra\Datatables\Datatables;

class CountryPriceController extends Controller
{
	public function index()
	{
		$countries = Country::where('id', '<>', 152)->select('id', 'name')->get();

		return view('admin-intl-country-prices', compact('countries'));
	}

	public function getCountryList(Request $request)
	{
		$countries = CountryPrice::with('country')
								->where('intl_purchase_id', $request->intl_purchase_id);

		return datatables()->of($countries)
							->editColumn('updated_at', function ($row) {
								return Carbon::parse($row->updated_at)->timezone('Asia/Yangon')->format('d M Y H:i:s');
							})
							->addColumn('action', function ($row) {
								$edit_btn = '<a href="javascript:void(0)" class="edit_price" data-id="' . $row->id . '" title="Edit Price"><i class="fas fa-edit"></i></a> | ';
								$delete_btn = '<a href="javascript:void(0)" class="delete_price" data-id="' . $row->id . '" title="Delete Price"><i class="far fa-trash-alt text-danger"></i></a>';

								$column = $edit_btn . '' . $delete_btn;

								return $column;
							})
							// ->filter(function ($query) use ($request) {
								// if ($request->has('purchase_date') && $request->get('purchase_date') != '') {
								// 	$query->whereDate('purchase_date', Carbon::parse($request->purchase_date)->format('Y-m-d') );
								// }
							// }, TRUE)
							->toJson();
	}

	public function create(Request $request)
	{
		$validator = self::validateCountry($request->all());
		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = CountryPrice::where('country_id', $request->country_id)
							->where('intl_purchase_id', $request->intl_purchase_id)
							->first();

		if ($check) {
			return response()->json(['status' => false, 'message' => ['country' => 'The selected country is already added.']], 200);
		}

		$country = CountryPrice::create($request->all());

		return response()->json(['status' => true, 'country' => $country], 200);
	}

	public static function validateCountry($request)
	{
		return Validator::make($request, [
			'country_id' => 'required',
			'intl_purchase_id' => 'required',
			'price' => 'required|numeric'
		]);
	}

	public function edit($id)
	{
		$country = CountryPrice::where('id', $id)->first();

		if ($country) {
			return response()->json(['status' => true, 'country' => $country], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}

	public function update(Request $request)
	{
		$validator = self::validateCountry($request->all());
		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check = CountryPrice::where('country_id', $request->country_id)
							->where('intl_purchase_id', $request->intl_purchase_id)
							->where('id', '<>', $request->id)
							->first();

		if ($check) {
			return response()->json(['status' => false, 'message' => ['country' => 'The selected country is already added.']], 200);
		}

		$country = CountryPrice::where('id', $request->id)->first();

		if ($country) {
			$country->update($request->all());
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}

	public function delete(Request $request)
	{
		$country = CountryPrice::find($request->id);

		if ($country) {
			$country->delete();

			return response()->json(['status' => true, 'data' => $country], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}
}