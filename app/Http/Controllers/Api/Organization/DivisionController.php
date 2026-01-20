<?php

namespace App\Http\Controllers\Api\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;

class DivisionController extends Controller
{
    public function index()
    {
        return response()->json(Division::with('departments')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'div_code' => 'required|unique:hris_divisions,div_code',
            'div_name' => 'required|string|max:255',
        ]);

        $division = Division::create($validated);
        return response()->json(['message' => 'Division created', 'data' => $division], 201);
    }

    public function show($id)
    {
        return Division::with('departments')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        $division->update($request->all());

        $division->refresh(); 

        return response()->json([
            'message' => 'Division updated successfully',
            'data'    => $division // Mengembalikan data terupdate
        ], 200);
    }

    public function destroy($id)
    {
        Division::findOrFail($id)->delete();
        return response()->json(['message' => 'Division deleted']);
    }
}
