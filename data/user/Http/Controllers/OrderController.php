<?php
namespace User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use User\Http\Controllers\Order\UserOrder;
use App\Http\Controllers\SendSmsWithAdminToken;

use App\Models\OrderNotification;
use App\Models\UserPackage;
use App\Models\LogDetail;

use GuzzleHttp\Client;
use Pusher\Pusher;
use Carbon\Carbon;

class OrderController extends Controller
{
	use UserOrder;
	use SendSmsWithAdminToken;

	public function orderConfirmationView(Request $request)
	{
		$package = $this->getOrderByPackageId($request->package_id);

		if ($package) {
			if (count($package->promotions) == 1) {
				$package->remaining_promo = $this->getRemainingPromo($package);
			}

			return $this->redirectUserCheckOut($package);
		}

		return redirect()->back();
	}

	public function confirmOrder(Request $request)
	{
		$package = $this->getOrderByPackageId($request->package_id);

		if ($package) {
			if (count($package->promotions) == 1) {
				$remaining_promo = $this->getRemainingPromo($package);

				if ($remaining_promo > 0) {
					$order = $this->createPromoOrder($package);
				} else {
					$order = $this->createOrder($package);
				}
			} else {
				$order = $this->createOrder($package);
			}

			if ($order) {
				$this->sendSmsWithAdminToken(['+9595074149', '+959955074149'], Auth::guard('web')->user()->username . ' ordered ' . $package->packageName . '. ' . Auth::guard('web')->user()->mobile);

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

				$data['user_package_id'] = $order->id;
				$data['package_id'] = $order->package_id;
				$data['message'] = Auth::guard('web')->user()->username . ' ordered ' . $package->packageName . ' at ' . Carbon::parse($order->created_at)->format('d M Y H:i:s');

				$notification = OrderNotification::create($data);

				if ($notification) {
					$pusher->trigger('order-notifications', 'order-received', $data);
				}

				return $this->redirectSuccessOrderToUser();
			}
		}

		return redirect()->back();
	}

	private function getRemainingPromo($package)
	{
		$user_package = UserPackage::where('promotion_id', $package->promotions{0}->id)
									->where('user_id', Auth::guard('web')->user()->id)
									->where('status', '<>', 'cancel');
		$used_promo = $user_package->count('promotion_id');

		$remaining_promo = $package->promotions{0}->max_purchase - $used_promo;
		return $remaining_promo;
	}

	private function createPromoOrder($package)
	{
		return UserPackage::create([
			'invoice_no' => Carbon::now()->timestamp,
			'user_id' => Auth::guard('web')->user()->id,
			'package_id' => $package->id,
			'promotion_id' => $package->promotions{0}->id,
			'credit' => $package->credit,
			'extra_credit' => $package->promotions{0}->promo_credit,
			'total_credit' => $package->credit + $package->promotions{0}->promo_credit,
			'cost' => $package->cost,
			'payment_method' => 'Bank',
			'status' => 'pending',
			'order_date' => Carbon::now()
		]);
	}
}