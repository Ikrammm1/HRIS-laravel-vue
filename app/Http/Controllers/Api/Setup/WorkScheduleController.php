<?php

namespace App\Http\Controllers\Api\Setup;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index()
    {
        $data = WorkSchedule::with('shift')->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'      => 'required|unique:work_schedules,code',
            'name'      => 'required',
            'work_days' => 'required|array|min:1',
            'shift_id'  => 'required|exists:shifts,id',
        ]);

        $data = WorkSchedule::create([
            ...$request->only(['code','name','work_days','shift_id','is_active']),
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'data' => $data->load('shift')], 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = WorkSchedule::findOrFail($id);

        $request->validate([
            'code'      => 'required|unique:work_schedules,code,' . $id,
            'name'      => 'required',
            'work_days' => 'required|array|min:1',
            'shift_id'  => 'required|exists:shifts,id',
        ]);

        $schedule->update($request->only(['code','name','work_days','shift_id','is_active']));

        return response()->json(['success' => true, 'data' => $schedule->load('shift')]);
    }

    public function destroy($id)
    {
        WorkSchedule::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Work schedule deleted']);
    }
}
