<?php


namespace Admin\Http\Controllers;


use App\Models\DefaultSetting;
use Illuminate\Http\Request;
use Validator;
class DefaultSettingController extends Controller
{
    public function manage(Request $request){
        $validator = Validator::make($request->all(), [
            'sender' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 200);
        }

        $setting =  DefaultSetting::first();
        if($setting){
            $setting->update($request->all());
        }else{
            DefaultSetting::create($request->all());
        }

        return response()->json(['success' => true]);

    }

    public function index(){
        $default_setting = DefaultSetting::first();
        return response()->json(['default_setting'=>$default_setting]);
    }
}