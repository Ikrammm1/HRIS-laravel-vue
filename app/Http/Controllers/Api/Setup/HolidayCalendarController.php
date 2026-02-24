<?php

namespace App\Http\Controllers\Api\Setup;


use App\Http\Controllers\Controller;
use App\Models\HolidayCalendar;
use Illuminate\Http\Request;

class HolidayCalendarController extends Controller
{
    public function index()
    {
        $data = HolidayCalendar::orderBy('date', 'asc')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required|date',
            'type' => 'required|in:national,company',
        ]);

        $data = HolidayCalendar::create([
            ...$request->only(['name','date','type','description','is_active']),
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'data' => $data], 201);
    }

    public function update(Request $request, $id)
    {
        $holiday = HolidayCalendar::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'date' => 'required|date',
            'type' => 'required|in:national,company',
        ]);

        $holiday->update($request->only(['name','date','type','description','is_active']));

        return response()->json(['success' => true, 'data' => $holiday]);
    }

    public function destroy($id)
    {
        HolidayCalendar::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Holiday deleted']);
    }
}