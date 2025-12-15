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
        }catch(Execption $e){
            $status = false;
            $message = $e;
            $data = [];

        }

        return response()->json([
            'success'   => $status,
            'message'   => $message,
            'data'      => $data
        ]);
    }



}
