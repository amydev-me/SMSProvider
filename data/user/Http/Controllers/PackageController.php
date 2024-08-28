<?php
namespace User\Http\Controllers;

use App\Models\UserPackage;
use App\Models\Package;

class PackageController extends Controller
{
	public function showPricing()
	{
		$packages = Package::where('packageName','<>','Free')->where('active',1)->orderBy('cost')->get();

		foreach($packages as $package) {
			if (count($package->promotions) == 1) {
				$remaining_promo = $this->getRemainingPromo($package);
				$package->remaining_promo = $remaining_promo;
			}
		}

		return view('buy', compact('packages'));
	}

	public function getCountries()
	{
		$countries = Country::with('operators')
							->where('rate', '>', 0)
							->where('status', '1')
							->paginate(10);

		return response()->json($countries);
	}

	public function search($name)
	{
		$countries = Country::with('operators')
							->where('rate', '>', 0)
							->where('status', '1')
							->where('name', 'LIKE', '%' . $name . '%')
							->orWhere('rate', 'LIKE', '%' . $name . '%')
							->orWhereHas('operators', function($query) use ($name) {
								$query->where('name', 'LIKE', '%' . $name . '%');
							})
							->paginate(10);

		return response()->json($countries);
	}

	private function getRemainingPromo($package)
	{
		$user_package = UserPackage::where('promotion_id', $package->promotions{0}->id)->where('status', '<>', 'cancel');
		$used_promo = $user_package->count('promotion_id');

		$remaining_promo = $package->promotions{0}->max_purchase - $used_promo;
		return $remaining_promo;
	}
}