<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsLog;
use App\Models\LogDetail;
use Carbon\Carbon;
use Auth;

use Yajra\Datatables\Datatables;

class OperatorLogController extends Controller
{
	public function index()
	{
		return view('admin-views.operator-log');
	}

	public function getSmsLogs(Request $request)
	{
		if ($request->has('user_id') && $request->get('user_id') != '') {
			$logs = SmsLog::where('user_id', $request->get('user_id'));
		} else {
			$logs = SmsLog::where('admin_id', Auth::guard('admin')->user()->id);
		}

		return Datatables::of($logs)
						->addIndexColumn()
						->addColumn('index', function($row) {
							return $row;
						})
						->editColumn('created_at', function($row) {
							$create_sms = strtotime($row->create_sms);
							return date('d M Y', $create_sms) . '<br/>' . date('h:i:s A', $create_sms);
						})
						->addColumn('recipients', function($row) {
							$column = '';
							$count = 0;

							$recipients = LogDetail::where('sms_log_id', $row->id)->pluck('recipient');

							foreach ($recipients as $recipient) {
								$column .= $recipient . ', ';
								$count++;

								if ($count >= 3) {
									$column .= '...';
									break;
								}
							}

							$column = trim($column, ', ');

							return $column;
						})
						->editColumn('message_content', function ($row) {
							if (strlen($row->message_content) > 100) {
								return substr($row->message_content, 0, 100) . '...';
							}

							return $row->message_content;
						})
						->addColumn('detail', function ($row) use ($request) {
							if ($request->has('user_id') && $request->get('user_id') != '') {
								return '<a role="button" href="/dashboard-user/detail/' . $row->id . '" class="btn btn-danger btn-sm">Detail</a>';
							} else {
								return '<a role="button" href="/admin/operator-log/detail/' . $row->id . '" class="btn btn-danger btn-sm">Detail</a>';
							}
						})
						->rawColumns([ 'created_at', 'detail' ])
						->filter(function ($query) use ($request) {
							if ($request->has('created_at') && $request->get('created_at') != '') {
								$query->whereDate('created_at', Carbon::parse($request->created_at)->format('Y-m-d') );
							}

							if ($request->has('from_date') && $request->has('to_date')) {
								$from_date = date('Y-m-d', strtotime($request->get('from_date')));
								$to_date = date('Y-m-d', strtotime($request->get('to_date')));

								if ($from_date != '1970-01-01' && $to_date != '1970-01-01') {
									$query->whereDate('created_at', '>=', $from_date);
									$query->whereDate('created_at', '<=', $to_date);
								}
							}
						}, TRUE)
						->toJson();
	}

	public function getLogDetails($id)
	{
		$sms_log = SmsLog::with('log_details')
							->where('id', $id)
							->first();

		$sms_log_details = LogDetail::with('country')
									->where('sms_log_id', $sms_log->id)
									->get();

		return view('admin-views.operator-detail', compact('sms_log', 'sms_log_details'));
	}

	public function getLogById(Request $request)
	{
		$sms_log = SmsLog::with(['log_details' => function($q) {
				$q->select(['id', 'recipient', 'sms_log_id']);
			}])->where('id', $request->log_id)->first();

		return response()->json($sms_log);
	}
}