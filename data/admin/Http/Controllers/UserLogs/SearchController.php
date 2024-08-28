<?php

namespace Admin\Http\Controllers\UserLogs;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OperatorLog;
use App\Models\LogDetail;
use App\Models\SmsLog;

class SearchController extends Controller
{
	public function search(Request $request)
	{
		if ($request->search_type == 'log_id') {
			$logs = LogDetail::whereHas('sms_log', function ($query) use ($request) {
								$query->where('id', $request->keyword);
							})
							->paginate(10);

			if ($logs->count() > 0) {
				$custom = collect(['log_id' => $request->keyword]);
				$logs = $custom->merge($logs);
			}
		} else {
			$logs = LogDetail::where('id', $request->keyword)
							->paginate(10);

			$log_id = SmsLog::whereHas('log_details', function ($query) use ($request) {
									$query->where('id', $request->keyword);
								})
								->value('id');

			$custom = collect(['log_id' => $log_id]);
			$logs = $custom->merge($logs);
		}

		if ($logs->count() > 0) {
			$logs['data'] = $this->getOperatorStatus($logs['data']);
		}

		return $logs;
	}

	private function getOperatorStatus($data)
	{
		$result = [];

		foreach ($data as $key) {
			$operator_status = NULL;

			if ($key['message_id'] != NULL) {
				$operator_status = OperatorLog::where('message_id', $key['message_id'])->value('status');
			}

			$key['operator_status'] = $operator_status;

			$result[] = $key;
		}

		return $result;
	}
}