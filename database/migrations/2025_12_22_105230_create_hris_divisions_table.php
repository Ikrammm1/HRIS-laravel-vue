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
    Schema::create('hris_divisions', function (Blueprint $table) {
        $table->id();
        $table->string('div_code')->unique(); // Contoh: IT, HRD, FIN
        $table->string('div_name');           // Contoh: Information Technology
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hris_divisions');
    }
};
