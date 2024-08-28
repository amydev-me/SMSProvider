<?php

namespace User\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LogDetail;
use App\Models\SmsLog;
use App\Models\Term;

use Carbon\Carbon;
use Auth;
use DB;

class DashboardController extends Controller
{
	public function showDashboard(Request $request)
	{
		$term = Term::latest()->first();

		$current_year = Carbon::now()->format('Y');
		$selected_year = $request->year;

		if (!$selected_year) {
			$selected_year = $current_year;
		}

		$register_year = Carbon::parse(Auth::guard('web')->user()->created_at)->format('Y');

		return view('dashboard', compact('term', 'current_year', 'selected_year', 'register_year'));
	}

	public function networkOperatorChart(Request $request)
	{
		$user_id = Auth::guard('web')->user()->id;

		$operator = LogDetail::whereHas('sms_log', function ($q) use ($user_id) {
								$q->where('user_id', $user_id);
							})
							->whereYear('created_at', $request->year)
							->groupBy('operator')
							->select('operator', DB::raw('count(*) as total_operator'))
							->orderBy('operator')
							->get();

		return $operator;
	}

	public function deliveryRate(Request $request)
	{
		$user_id = Auth::guard('web')->user()->id;

		$status = LogDetail::whereHas('sms_log', function ($q) use ($user_id) {
								$q->where('user_id', $user_id);
							})
							->whereYear('created_at', $request->year)
							->groupBy('status')
							->select('status', DB::raw('count(*) as total'))
							->orderBy('status')
							->get();

		$allcount = $status->sum('total');

		return response()->json(['status' => $status, 'allcount' => $allcount]);
	}

	public function sourceRate(Request $request)
	{
		$user_id = Auth::guard('web')->user()->id;

		$status = LogDetail::whereHas('sms_log', function ($q) use ($user_id) {
								$q->where('user_id', $user_id);
							})
							->whereYear('created_at', $request->year)
							->groupBy('source')
							->select('source', DB::raw('count(*) as total'))
							->orderBy('source')
							->get();

		$allcount = $status->sum('total');

		return response()->json(['source' => $status, 'allcount' => $allcount]);
	}

	public function monthlyChart(Request $request)
	{
		$user_id = Auth::guard('web')->user()->id;

		$logs = SmsLog::where('user_id', $user_id)
						->whereYear('created_at', $request->year)
						->select(DB::raw('DATE_FORMAT(created_at, "%b") AS month'), DB::raw('SUM(total_sms) AS total_sms'))
						->groupBy('month')
						->get();

		$result = $logs->mapWithKeys(function ($item) {
						return [$item['month'] => $item['total_sms']];
					});


		return response()->json($result);
	}

	public function lastWeekChart()
	{
		$currentDate = Carbon::now();

		$agoDate = Carbon::now()->subDays($currentDate->dayOfWeek)->subWeek();

		$user_id = Auth::guard('web')->user()->id;

		$logs = SmsLog::where('user_id', $user_id)
						->where('created_at', '>', $agoDate->format('Y-m-d' . ' 12:00:00'))
						->where('created_at', '<', Carbon::now()->format('Y-m-d' . ' 23:59:59'))
						->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_sms) as total'))
						->groupBy('date')
						->get();

		return response()->json(['logs' => $logs->pluck('date'), 'data' => $logs->pluck('total')]);
	}
}