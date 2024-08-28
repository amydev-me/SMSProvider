<?php


namespace Admin\Http\Controllers;




use Illuminate\Http\Request;
use Validator;
use App\Models\Gateway;
class GatewayController extends Controller
{
    public function getGateways()
    {
        $gateways = Gateway::with('operator', 'country')->get();
        return response()->json($gateways);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            // 'operator_id' => 'required',
            'encoding' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid request.'], 400);
        }

        $gateway_count = Gateway::where('country_id',$request->country_id)
            ->where('operator_id',$request->operator_id)
            ->where('encoding',$request->encoding)->count();


        if($gateway_count>0){
            return response()->json(['status' => false, 'message' => 'Current Gateway Already added.'], 400);
        }


        $gateway = Gateway::create($request->all());

        if ($gateway) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'country_id' => 'required',
            'operator_id' => 'required',
            'encoding' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 200);
        }


        $gateway_count = Gateway::where('country_id',$request->country_id)
            ->where('operator_id',$request->operator_id)
            ->where('encoding',$request->encoding)->where('id','<>',$request->id)->count();


        if($gateway_count>0){
            return response()->json(['status' => false, 'message' => 'Current Gateway Already added.'], 400);
        }


        $gateway = Gateway::where('id', $request->id)->first();
        if ($gateway) {
            $gateway->update($request->except('id'));
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}