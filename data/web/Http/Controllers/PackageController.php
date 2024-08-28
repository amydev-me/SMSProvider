<?php
namespace Web\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Package;
use App\Models\Country;

class PackageController extends Controller
{
	public function showPricing()
	{
		$packages = Package::with(['promotions' => function ($query) {
								$query->where('active', '1');
							}])
							->where('active',1)
							->orderBy('cost')
							->get();

		return view('pricing', compact('packages'));
	}

	public function getCountries(Request $request)
	{
		$countries = Country::with('operators')
							->where('rate', '>', 0)
							->where('status', '1')
							->paginate($request->page_size);

		return response()->json($countries);
	}

	public function search(Request $request, $name)
	{
		$countries = Country::with('operators')
							->where('rate', '>', 0)
							->where('status', '1')
							->where('name', 'LIKE', '%' . $name . '%')
							->orWhere('rate', 'LIKE', '%' . $name . '%')
							->orWhereHas('operators', function($query) use ($name) {
								$query->where('name', 'LIKE', '%' . $name . '%');
							})
							->paginate($request->page_size);

		return response()->json($countries);
	}
}