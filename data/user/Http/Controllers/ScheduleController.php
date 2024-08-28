<?php

namespace User\Http\Controllers;






use App\Models\ScheduleMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $messages = ScheduleMessage::where('user_id', Auth::guard('web')->user()->id)->orderBy('created_at','asc')->paginate(10);

        return view('schedule_list', compact('messages'));
    }

    public function cancelSchedule(Request $request)
    {
        $message = ScheduleMessage::where('user_id', Auth::guard('web')->user()->id)->where('id', $request->schedule_id)->first();
        if ($message) {
            $message->delete();
            return back();
//            return response()->json(['status'=>'success','code'=>'200','message'=>'success'],200);
        }
        return back();

    }
}