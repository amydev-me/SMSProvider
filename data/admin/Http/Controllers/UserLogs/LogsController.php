<?php

namespace Admin\Http\Controllers\UserLogs;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LogDetail;
use App\Models\SmsLog;
use App\Models\User;
use Carbon\Carbon;
use DB;

use Yajra\Datatables\Datatables;

class LogsController extends Controller
{
	public function index()
	{
		return view('admin-view-logs.users');
	}

	public function getUsers(Request $request)
	{
		$users = User::where('obsolete', '<>', '1');

		return Datatables::of($users)
						->addColumn('action', function ($row) {
							$view_btn = '<a href="/dashboard-user/view/' . $row->id . '" class="view_user" title="View User"><i class="far fa-eye text-info"></i></a>';

							$column = $view_btn;

							return $column;
						})
						->rawColumns([ 'action' ])
						->filter(function ($query) use ($request) {
							if ($request->has('account_type') && $request->get('account_type') != '') {
								$query->where('account_type', $request->get('account_type'));
							}
						}, TRUE)
						->toJson();
	}

	public function viewUserLog(Request $request, $id)
	{
		$user = User::find($id);

		if ($user) {
			$request->session()->put('user_data', $user);
			return view('admin-view-logs.user-logs', compact('user', 'id'));
		}

		return back();
	}

	public function getLogs(Request $request)
	{
		$logs = SmsLog::where('user_id', $request->get('user_id'));

		return Datatables::of($logs)
						->addIndexColumn()
						->addColumn('index', function($row) {
							return $row;
						})
						->editColumn('created_at', function($row) {
							$create_sms = strtotime($row->create_sms);
							return date('d-m-Y', $create_sms) . '<br/>' . date('h:i:s A', $create_sms);
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
								return '<a role="button" href="/admin/operator/detail/' . $row->id . '" class="btn btn-danger btn-sm">Detail</a>';
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

	public function getLogDetail($id)
	{
		$sms_log = SmsLog::with('log_details')
							->where('id', $id)->first();

		return view('admin-view-logs.detail', compact('sms_log'));
	}
}