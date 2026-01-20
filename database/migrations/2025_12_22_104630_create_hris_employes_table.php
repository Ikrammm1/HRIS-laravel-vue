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
        Schema::create('hris_employes', function (Blueprint $table) {
            $table->id();
            $table->string('div_code');       // Kode Divisi
            $table->string('dept_code');      // Kode Departemen
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('position');       // Jabatan
            $table->string('employee_status'); // Contoh: Permanent, Contract, Probation
            $table->date('join_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hris_employes');
    }
};
