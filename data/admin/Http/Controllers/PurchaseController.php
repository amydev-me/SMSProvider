<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Balance;
use Carbon\Carbon;
use Validator;

use Yajra\Datatables\Datatables;

class PurchaseController extends Controller
{
	public function index()
	{
		return view('admin-views.purchase');
	}

	public function getPurchaseList(Request $request)
	{
		$purchases = Purchase::with('balances')->where('obsolete', '<>', '1');

		return datatables()->of($purchases)
							->editColumn('purchase_date', function ($row) {
								return Carbon::parse($row->purchase_date)->format('d M Y');
							})
							->addColumn('action', function ($row) {
								$edit_btn = '<a href="javascript:void(0)" class="edit_purchase" data-id="' . $row->id . '" title="Edit Purchase"><i class="fas fa-edit"></i></a> | ';
								$delete_btn = '<a href="javascript:void(0)" class="delete_purchase" data-id="' . $row->id . '" title="Delete Purchase"><i class="far fa-trash-alt text-danger"></i></a>';

								$column = $edit_btn . '' . $delete_btn;

								return $column;
							})
							->filter(function ($query) use ($request) {
								if ($request->has('purchase_date') && $request->get('purchase_date') != '') {
									$query->whereDate('purchase_date', Carbon::parse($request->purchase_date)->format('Y-m-d') );
								}
							}, TRUE)
							->toJson();
	}

	public function create(Request $request)
	{
		$validator = self::validatePurchase($request->all());

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$params = $request->all();
		$params['purchase_date'] = Carbon::parse($params['purchase_date'])->format('Y-m-d');

		$purchase = Purchase::create($params);

		if ($purchase) {
			Balance::create(['purchase_id' => $purchase->id, 'balance' => $purchase->amount]);
		}

		return response()->json(['status' => true, 'purchase' => $purchase], 200);
	}

	public static function validatePurchase($request)
	{
		return Validator::make($request, [
			'amount' => 'required|numeric',
			'purchase_date' => 'required|date_format:d M Y',
			'mpt_price' => 'required|numeric',
			'telenor_price' => 'required|numeric',
			'ooredoo_price' => 'required|numeric',
			'mytel_price' => 'required|numeric',
			'mec_price' => 'required|numeric',
		]);
	}

	public function edit($id)
	{
		$purchase = Purchase::where('id', $id)->first()->toArray();

		$purchase['purchase_date'] = Carbon::parse($purchase['purchase_date'])->format('d-m-Y');

		if ($purchase) {
			return response()->json(['status' => true, 'purchase' => $purchase], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}

	public function update(Request $request)
	{
		$validator = self::validatePurchase($request->all());

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$purchase = Purchase::where('id', $request->id)->first();

		if ($purchase) {
			$params = $request->all();
			$params['purchase_date'] = Carbon::parse($params['purchase_date'])->format('Y-m-d');
			$purchase->update($params);
			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't  access data."], 403);
	}

	public function delete(Request $request)
	{
		$purchase = Purchase::find($request->id);

		if ($purchase) {
			$purchase->obsolete = '1';
			$purchase->save();

			return response()->json(['status' => true, 'data' => $purchase], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}
}