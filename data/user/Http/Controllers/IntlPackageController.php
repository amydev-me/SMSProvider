<?php
namespace User\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\IntlOrderNotification;
use App\Models\CountryPrice;
use App\Models\IntlPurchase;
use App\Models\UserCredit;
use App\Models\User;

use Pusher\Pusher;
use Carbon\Carbon;
use Validator;
use Auth;

class IntlPackageController extends Controller
{
	public function showPricing()
	{
		return view('intl-buy', compact('pricings'));
	}

	public function getAllPricing()
	{
		$purchase = IntlPurchase::where('obsolete', '0')
								->where('out_of_balance', '0')
								->first();

		$pricings = CountryPrice::join('countries', 'countries.id', '=', 'country_id')
								->where('intl_purchase_id', $purchase->id)
								->select('countries.name', 'price')
								->orderBy('countries.name', 'asc')
								->paginate(10);

		return response()->json($pricings);
	}

	public function filterPricingByCountry(Request $request)
	{
		$purchase = IntlPurchase::where('obsolete', '0')
								->where('out_of_balance', '0')
								->first();

		$pricings = CountryPrice::join('countries', 'countries.id', '=', 'country_id')
								->where('intl_purchase_id', $purchase->id)
								->where('countries.name', 'like', '%' . $request->param . '%')
								->select('countries.name', 'price')
								->orderBy('countries.name', 'asc')
								->paginate(10);

		return response()->json($pricings);
	}

	public function checkout()
	{
		$is_verify = User::CurrentUser()->where('verified', true)->exists();
		return view('intl-checkout', compact('is_verify'));
	}

	public function confirm(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'amount' => 'required|integer|min:10000'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$order = UserCredit::create([
			'invoice_no' => Carbon::now()->timestamp,
			'user_id' => Auth::guard('web')->user()->id,
			'amount' => $request->amount,
			'payment_method' => 'Bank',
			'status' => 'pending',
			'order_date' => Carbon::now()
		]);

		if ($order) {
			$options = array(
				'cluster' => 'ap1',
				'encrypted' => true
			);

			$pusher = new Pusher(
				'a5eab64bb5e6af5ac31d',
				'21626345a9b8a8e2f1d4',
				'563963',
				$options
			);

			$data['user_credit_id'] = $order->id;
			$data['message'] = Auth::guard('web')->user()->username . ' ordered ' . $order->amount . ' credits at ' . Carbon::parse($order->created_at)->format('d M Y H:i:s');

			$notification = IntlOrderNotification::create($data);

			if ($notification) {
				$pusher->trigger('order-notifications', 'order-received', $data);
			}

			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}
}