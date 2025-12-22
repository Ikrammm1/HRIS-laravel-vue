<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {

        $data = Menu::all();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $menu = Menu::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Menu created successfully',
            'data' => $menu
        ], 201);
    }

    public function update(Request $request)
    {
        $menu = Menu::find($request->id);
        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu not found'
            ], 404);
        }

        $menu->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Menu updated successfully',
            'data' => $menu
        ], 200);
    }
    
    public function delete(Request $request)
    {
        $menu = Menu::find($request->id);
        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu not found'
            ], 404);      
        } 
        $menu->delete();
        return response()->json([
            'success' => true,
            'message' => 'Menu deleted successfully'
        ], 200);
    }

    public function authorizeMenu(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'data' => []
            ], 401);
        }

        $menus = Menu::whereHas('authorizationGroups.users', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })
        ->orderBy('level')
        ->orderBy('id')
        ->get();

        $headers = [];
        $parents = [];

        /**
         * =========================
         * LEVEL 1 — HEADER
         * =========================
         */
        foreach ($menus as $menu) {
            if ($menu->level == 1) {
                $headers[$menu->id] = [
                    'name' => $menu->menu_name,
                    'to' => $menu->url ?: null,
                    'icon' => $menu->icon,
                    'requiresAbility' => $menu->description,
                    'showDesktop' => true,
                    'showMobile' => true,
                    'children' => []
                ];
            }
        }

        /**
         * =========================
         * LEVEL 2 — PARENT
         * =========================
         */
        foreach ($menus as $menu) {
            if ($menu->level == 2 && isset($headers[$menu->parent_id])) {
                $parents[$menu->id] = [
                    'name' => $menu->menu_name,
                    'to' => $menu->url ?: null,
                    'icon' => $menu->icon,
                    'requiresAbility' => $menu->description,
                    'showDesktop' => true,
                    'showMobile' => true,
                    'children' => []
                ];

                $headers[$menu->parent_id]['children'][] = &$parents[$menu->id];
            }
        }

        /**
         * =========================
         * LEVEL 3 — CHILDREN
         * =========================
         */
        foreach ($menus as $menu) {
            if ($menu->level == 3 && isset($parents[$menu->parent_id])) {
                $parents[$menu->parent_id]['children'][] = [
                    'name' => $menu->menu_name,
                    'to' => $menu->url,
                    'icon' => $menu->icon,
                    'requiresAbility' => $menu->description,
                    'showDesktop' => true,
                    'showMobile' => true,
                    'children' => []
                ];
            }
        }

        $dataMenu = array_values($headers);

        return response()->json([
            'message' => 'Authorized menu fetched successfully.',
            'data' => $dataMenu
        ], 200);
    }

    
}
