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
        Schema::table('hris_employes', function (Blueprint $table) {
            $table->string('svp')->after('join_date')->nullable();
            $table->string('date_of_birth')->after('svp')->nullable();
            $table->string('place_of_birth')->after('date_of_birth')->nullable();
            $table->string('address')->after('place_of_birth')->nullable();
            $table->string('religion')->after('address')->nullable();
            $table->string('marital_status')->after('religion')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
