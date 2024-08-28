<?php
namespace Admin\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\ScheduleMessage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
	public function index()
	{
		$messages = ScheduleMessage::where('admin_id', Auth::guard('admin')->user()->id)
									->orderBy('created_at', 'desc')
									->paginate(10);

		return view('admin-views.schedule', compact('messages'));
	}

	public function cancelSchedule(Request $request)
	{
		$message = ScheduleMessage::where('admin_id', Auth::guard('admin')->user()->id)
									->where('id', $request->schedule_id)
									->first();

		if ($message) {
			$message->delete();
			return back();
		}

		return back();
	}
}