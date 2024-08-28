<?php

namespace User\Http\Controllers;


use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Web\Http\Controllers\TokenGenerate;

class ApiTokenController extends Controller
{
    public function getTokens()
    {
        $tokens = $user_id=Auth::guard('web')->user()->user_tokens;
        return response()->json($tokens);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), ['app_name' => 'required']);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => 'Error occured while creating token.'], 200);
        }
        $input = $request->all();
        $input['user_id'] =Auth::guard('web')->user()->id;
        $input['api_key'] = Auth::guard('web')->user()->username;
        $input['api_secret'] = (new TokenGenerate())->generateSecret();
        UserToken::create($input);
        return response()->json(['status' => true], 200);
    }


    public function deleteToken($id){
        $success=  UserToken::where('id',$id)->whereHas('user',function($query){
            $query->currentUser();
        })->firstOrFail()->delete();

        if($success) {
            return response()->json(['status' => true], 200);
        }else{
            return response()->json(['status' => false], 500);
        }
    }
}