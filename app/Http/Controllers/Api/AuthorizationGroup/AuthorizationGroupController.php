<?php

namespace App\Http\Controllers\Api\AuthorizationGroup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuthorizationGroup;
use Illuminate\Support\Facades\DB;

class AuthorizationGroupController extends Controller
{
    public function index()
    {
        $groups = AuthorizationGroup::with([
            'users:id,name,email',
            'menus:id,menu_name,level,parent_id'
        ])->get();

        return response()->json([
            'success' => true,
            'datas' => $groups
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'string',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
            'menu_ids' => 'array',
            'menu_ids.*' => 'exists:hris_menus,id',
        ]);

        DB::beginTransaction();

        try {
            // 1. Insert group
            $group = AuthorizationGroup::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id'   => $request->user_id
            ]);

            // 2. Insert pivot users
            if (!empty($request->user_ids)) {
                $group->users()->sync($request->user_ids);
            }

            // 3. Insert pivot menus
            if (!empty($request->menu_ids)) {
                $group->menus()->sync($request->menu_ids);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Authorization group created successfully',
                'data' => $group->load('users', 'menus')
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create authorization group',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    public function update(Request $request, $id)
    {
        $group = AuthorizationGroup::findOrFail($id);

        DB::transaction(function () use ($request, $group) {
            $group->update($request->only('name', 'description'));

            if ($request->has('user_ids')) {
                $group->users()->sync($request->user_ids);
            }

            if ($request->has('menu_ids')) {
                $group->menus()->sync($request->menu_ids);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Authorization group updated successfully',
            'data' => $group->load('users', 'menus')
        ]);

    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $group = AuthorizationGroup::findOrFail($id);

            // Optional: detach manual (tidak wajib kalau FK cascade sudah benar)
            $group->users()->detach();
            $group->menus()->detach();

            // Delete group
            $group->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Authorization group deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Authorization group not found'
            ], 404);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete authorization group',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
