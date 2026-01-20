<?php

namespace App\Http\Controllers\Api\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return response()->json(Department::with('division')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dept_code' => 'required|unique:hris_departments,dept_code',
            'dept_name' => 'required|string|max:255',
            'div_code'  => 'required|exists:hris_divisions,div_code',
        ]);

        $department = Department::create($validated);
        return response()->json(['message' => 'Department created', 'data' => $department], 201);
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $department->update($request->all());

        $department->load('division');

        return response()->json([
            'message' => 'Department updated successfully',
            'data'    => $department // Mengembalikan data terupdate
        ], 200);
    }

    public function destroy($id)
    {
        Department::findOrFail($id)->delete();
        return response()->json(['message' => 'Department deleted']);
    }
}
