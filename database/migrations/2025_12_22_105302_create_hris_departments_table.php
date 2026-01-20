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
        Schema::create('hris_departments', function (Blueprint $table) {
            $table->id();
            $table->string('dept_code')->unique();
            $table->string('dept_name');          
            $table->string('div_code');            
            $table->timestamps();

            // Opsional: Menjamin integritas data dengan Foreign Key
            $table->foreign('div_code')->references('div_code')->on('hris_divisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hris_departments');
    }
};
