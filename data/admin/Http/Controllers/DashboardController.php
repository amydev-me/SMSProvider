<?php

namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserPackage;
use App\Models\PaygInvoice;
use App\Models\LogDetail;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$users = User::where('obsolete', '0');
		$orders = UserPackage::where('cost', '<>', '0')->where('status', 'paid');
		$payg_orders = PaygInvoice::where('cost', '<>', '0')->where('status', 'paid');

		$current_year = Carbon::now()->format('Y');
		$selected_year = $request->year;

		if (!$selected_year) {
			$selected_year = $current_year;
		}

		return view('admin-views.dashboard', compact('users', 'orders', 'payg_orders', 'current_year', 'selected_year'));
	}

	public function getOperatorsUsage(Request $request)
	{
		$operators = LogDetail::whereYear('created_at', $request->year)
								->groupBy('operator')
								->select('operator', DB::raw('count(*) AS total_sms'))
								->orderBy('operator')
								->get();

		return response()->json($operators);
	}

	public function getDeliveryRate(Request $request)
	{
		$deliveries = LogDetail::whereYear('created_at', $request->year)
								->groupBy('status')
								->select('status', DB::raw('count(*) AS total'))
								->orderBy('status')
								->get();

		$all_count = $deliveries->sum('total');

		return response()->json(['deliveries' => $deliveries, 'all_count' => $all_count]);
	}

	public function getPackageUsage(Request $request)
	{
		$packages = UserPackage::join('packages AS p', 'p.id', '=', 'user_packages.package_id')
								->whereYear('user_packages.created_at', $request->year)
								->where('user_packages.status', '=', 'paid')
								->where('user_packages.package_id', '<>', 1)
								->groupBy('user_packages.package_id', 'p.packageName')
								->select('p.packageName', DB::raw('count(*) AS total_packages'))
								->get();

		$package_names = Package::where('id', '<>', 1)->pluck('packageName');

		return response()->json(['packages' => $packages, 'package_names' => $package_names]);
	}

	public function getUserRegistration(Request $request)
	{
		$users = User::whereYear('created_at', $request->year)
					->select(DB::raw('MONTH(created_at) month'), DB::raw('DATE_FORMAT(created_at, "%b") AS created_month'), DB::raw('count(*) AS total_users'))
					->groupBy('month', 'created_month')
					->orderBy('month')
					->get();

		return response()->json($users);
	}

	public function getPackageBarChart(Request $request)
	{
		$packages = UserPackage::whereYear('created_at', $request->year)
								->where('cost', '<>', '0')
								->where('status', 'paid')
								->select(DB::raw('MONTH(created_at) month'), DB::raw('DATE_FORMAT(created_at, "%b") AS created_month'), DB::raw('count(*) AS total_packages'))
								->groupBy('month', 'created_month')
								->orderBy('month')
								->get();

		return response()->json($packages);
	}

	public function getPhpInfo()
	{
		phpinfo();
	}
}