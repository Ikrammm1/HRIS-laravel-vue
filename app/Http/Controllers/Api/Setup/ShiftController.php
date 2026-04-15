<?php

namespace App\Http\Controllers\Api\Setup;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $data = Shift::orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'       => 'required|unique:hris_shifts,code',
            'name'       => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
            ]);

        $data = Shift::create([
            ...$request->only(['code','name','start_time','end_time','break_duration','is_overnight','is_active']),
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'data' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:hris_shifts,code,' . $id,
            'name' => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $shift->update($request->only(['code','name','start_time','end_time','break_duration','is_overnight','is_active']));

        return response()->json(['success' => true, 'data' => $shift]);
    }

    public function destroy($id)
    {
        Shift::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Shift deleted']);
    }
}