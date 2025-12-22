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
        // 1. Dapatkan pengguna yang saat ini login
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'data' => []
            ], 401);
        }

        // 2. Definisikan struktur menu penuh
        // Di sini Anda dapat memuatnya dari file konfigurasi, database, atau array statis
        $fullMenu = [
            [
                'name' => 'Dashboard',
                'to' => '/dashboard',
                'icon' => 'home',
                'requiresAbility' => 'view dashboard', // Contoh permission/ability
                'showDesktop' => true,
                'showMobile' => true,
            ],
            [
                'name' => 'Karyawan',
                'icon' => 'user-tie',
                'requiresAbility' => 'manage employee', 
                'showDesktop' => true,
                'showMobile' => true,
                'children' => [
                    [
                        'name' => 'Data Karyawan',
                        'to' => '/employee/list',
                        'requiresAbility' => 'view employee list',
                    ],
                    [
                        'name' => 'Pengajuan Cuti',
                        'to' => '/employee/leave',
                        'requiresAbility' => 'submit leave',
                    ],
                ]
            ],
            [
                'name' => 'Pengaturan',
                'icon' => 'cog',
                'requiresAbility' => 'manage settings', 
                'showDesktop' => true,
                'showMobile' => true,
                'children' => [
                    [
                        'name' => 'Users & Roles',
                        'to' => '/settings/access',
                        'requiresAbility' => 'manage user access',
                    ],
                    [
                        'name' => 'System Logs',
                        'to' => '/settings/logs',
                        'requiresAbility' => 'view logs',
                    ],
                    [
                        'name' => 'Menu Management',
                        'to' => '/settings/menu',
                        'requiresAbility' => 'view menu management',
                    ],
                    [
                        'name' => 'User Management',
                        'to' => '/settings/user',
                        'requiresAbility' => 'view user management',
                    ],
                    [
                        'name' => 'Authorization Group',
                        'to' => '/settings/autherization-group',
                        'requiresAbility' => 'view authorization group',
                    ],
                ]
            ],
            
        ];

        // 3. Filter menu berdasarkan otorisasi pengguna
        // $authorizedMenu = $this->filterMenu($fullMenu, $user);

        // 4. Kembalikan menu yang diotorisasi
        return response()->json([
            'message' => 'Authorized menu fetched successfully.',
            'data' => $fullMenu
        ], 200);
    }

    
}
