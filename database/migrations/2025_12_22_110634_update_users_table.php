<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom foreign key setelah kolom 'id'
            // constrained() secara otomatis merujuk ke tabel 'hris_employes' jika modelnya HrisEmploye
            $table->foreignId('employe_id')
                ->nullable() // Dibuat nullable agar user lama tetap bisa login tanpa harus punya link karyawan
                ->after('id') 
                ->constrained('hris_employes')
                ->onDelete('cascade'); // Jika data karyawan dihapus, user ini ikut terhapus
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employe_id');
        });
    }
};
