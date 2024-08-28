<?php


namespace Admin\Http\Controllers;



use App\Models\DefaultEndpoint;
use Illuminate\Http\Request;
use Validator;
class DefaultEndpointController extends Controller
{
    public function endpointList(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'gateway_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 200);
        }

        $list = DefaultEndpoint::with(
            ['telecom' => function ($q) {
                $q->select(['id', 'name']);
            }, 'gateway' => function ($q) {
                $q->with('operator', 'country');
            }])
            ->where('gateway_id', $request->gateway_id)
            ->get();
        return response()->json($list);
    }

    /**
     * User Can Set Only One Endpoint
     */
    public function setDefaultEndpoint(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'gateway_id' => 'required',
            'active_endpoint' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 200);
        }

        $active_endpoint = DefaultEndpoint::where('gateway_id', $request->gateway_id)->where('id', '<>', $request->id)->where('active_endpoint', 1)->first();

        if (!$active_endpoint) {
            return response()->json(['status' => false, 'message' => 'Need to set at least one default endpoint.'], 200);
        }


        $default_endpoint = DefaultEndpoint::where('id', $request->id)->first();

        if ($default_endpoint) {
            $default_endpoint->update([
                'active_endpoint' => true
            ]);
        }

        if ($active_endpoint) {
            $active_endpoint->update([
                'active_endpoint' => false
            ]);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway_id' => 'required',
            'telecom_id' => 'required',
            'active_endpoint' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Invalid Request.'], 400);
        }

        $gateway_opt =  DefaultEndpoint::where('gateway_id',$request->gateway_id)->where('telecom_id',$request->telecom_id)->count();

        if ($gateway_opt>0) {
            return response()->json(['status' => false, 'message' => 'Current Telecom Already Added'], 400);
        }

        $gateway_count = DefaultEndpoint::where('gateway_id',$request->gateway_id)->count();

        if ($gateway_count <= 0) {
            $request['active_endpoint'] = true;
        }

        $gateway = DefaultEndpoint::create($request->all());

        if ($gateway) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'gateway_id' => 'required',
            'telecom_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 200);
        }

        $query = DefaultEndpoint::where('gateway_id',$request->gateway_id);

        $gateway_opt = $query
            ->where('id','<>',$request->id)
            ->where('telecom_id',$request->telecom_id)->count();

        if ($gateway_opt>0) {
            return response()->json(['status' => false, 'message' => 'Current Telecom Already Added'], 400);
        }

        $endpoint = DefaultEndpoint::where('id', $request->id)->first();
        if ($endpoint) {
            $endpoint->update($request->except('id'));
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}