<?php

namespace Admin\Http\Controllers;

use App\Models\Telecom;
use Illuminate\Http\Request;
use Validator;

class TelecomController extends Controller
{

    public function getTelecoms()
    {
        $telecoms = Telecom::all();
        return response()->json($telecoms);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'secret' => 'required',
            'end_point' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 200);
        }

        $request->name = strtoupper($request->name);

        $telecom = Telecom::create($request->all());

        if ($telecom) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'secret' => 'required',
            'end_point' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 200);
        }

        $telecom = Telecom::where('id', $request->id)->first();
        if ($telecom) {
            $request->name = strtoupper($request->name);
            $telecom->update($request->except('id'));
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function asyncTelecom()
    {
        $telecoms = Telecom::select(['id', 'name'])->get();

        return response()->json($telecoms);
    }
}