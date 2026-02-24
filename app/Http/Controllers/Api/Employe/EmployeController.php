<?php

namespace App\Http\Controllers\Api\Employe;

use App\Models\Employe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EmployeController extends Controller
{
    // Menampilkan semua karyawan
    public function index()
    {
        $employees = Employe::with('division', 'department')->get();
        return response()->json($employees);
    }

    // Menyimpan karyawan baru sekaligus membuatkan akun User
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hris_employes,email|unique:users,email',
            'div_code' => 'required',
            'dept_code' => 'required',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'required',
            'position' => 'required',
            'employee_status' => 'required',
            'join_date' => 'required',
            'svp' => 'nullable',
            'date_of_birth' => 'required',
            'place_of_birth' => 'required',
            'address' => 'nullable',
            'religion' => 'required',
            'merital_status' => 'required',

        ]);

        // Menggunakan Transaction agar jika salah satu gagal, keduanya batal tersimpan
        return DB::transaction(function () use ($request) {
            
            // 1. Simpan ke tabel hris_employes
            $employee = Employe::create([
                'div_code' => $request->div_code,
                'dept_code' => $request->dept_code,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
                'position' => $request->position,
                'employee_status' => $request->employee_status,
                'join_date' => $request->join_date,
                'svp' => $request->svp,
                'date_of_birth' => $request->date_of_birth,
                'place_of_birth' => $request->place_of_birth,
                'address' => $request->address,
                'religion' => $request->religion,
                'merital_status' => $request->merital_status,
            ]);

            // 2. Simpan ke tabel users (untuk login)
            User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'hris_employe_id' => $employee->id, // Foreign Key yang kita buat tadi
            ]);

            $employee = Employe::with('division', 'department')->where('id', $employee->id)->first(); // Pastikan data terbaru diambil

            return response()->json(['message' => 'Employee and User created successfully', 'data' => $employee], 200);
        });
    }

    // Menampilkan detail satu karyawan beserta data User-nya
    public function show($id)
    {
        $employee = Employe::with('user')->findOrFail($id);
        return response()->json($employee);
    }

    // Update data karyawan
    public function update(Request $request, $id)
    {
        $employee = Employe::findOrFail($id);
        $employee->update($request->all());

        $employee = Employe::with('division', 'department')->where('id', $employee->id)->first(); // Pastikan data terbaru diambil

        return response()->json(['message' => 'Employee updated successfully', 'data' => $employee], 200);
    }

    // Hapus karyawan (akan terhapus juga di tabel User karena 'cascade')
    public function destroy($id)
    {
        $employee = Employe::findOrFail($id);
        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully']);
    }
}
