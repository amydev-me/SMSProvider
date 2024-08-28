<?php
namespace User\Http\Controllers;

use App\Models\LogDetail;
use App\Models\SmsLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsLogController extends Controller
{
	public function index()
	{
		return view('sms_logs.index');
	}

	public function smsLogsFilterByDate(Request $request)
	{
		$fromdate = Carbon::parse($request->start_date)->format('Y-m-d');
		$todate = Carbon::parse($request->end_date)->format('Y-m-d');
		
		$logs = SmsLog::with('log_details')
					->whereDate('create_sms', '>=', $fromdate)
					->whereDate('create_sms', '<=', $todate)
					->where('user_id', Auth::guard('web')->user()->id)
					->orderByDESC('create_sms')
					->paginate(10);

		return response()->json($logs);
	}

	public function logDetailView(Request $request)
	{
		$sms_log = SmsLog::where('user_id', Auth::guard('web')->user()->id)
						->where('id', $request->log_id)
						->first();

		$sms_log_details = LogDetail::with('country')
									->where('sms_log_id', $sms_log->id)
									->get();

		return view('sms_logs.detail', compact('sms_log', 'sms_log_details'));
	}

	public function getLogById(Request $request)
	{
		$sms_log = SmsLog::with(['log_details'=>function($q){
			$q->select(['id','recipient','sms_log_id']);
		}])->where('id', $request->log_id)->first();


		return response()->json($sms_log);
	}
}