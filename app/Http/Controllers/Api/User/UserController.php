<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $data = User::all();

        return response()->json([
            'success' => true,
            'datas' =>$data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'email' => 'email'
        ]);

        try{
            $data = User::create([
                'name' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->pass)
            ]);
            $status = true;
            $message = 'Data has been added';
        }catch(Exception $e){
            $status = false;
            $message = $e->getMessage();
            $data = [];

        }

        return response()->json([
            'success'   => $status,
            'message'   => $message,
            'data'      => $data
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'       => 'required|exists:users,id',
            'username' => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'pass'     => 'nullable|min:6',
        ]);

        try {
            $user = User::findOrFail($request->id);

            $data = [
                'name'  => $request->username,
                'email' => $request->email,
            ];

            // Password hanya diupdate jika diisi
            if ($request->filled('pass')) {
                $data['password'] = bcrypt($request->pass);
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data'    => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            $user = User::findOrFail($request->id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus',
                'data'    => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => []
            ], 500);
        }
    }



}
