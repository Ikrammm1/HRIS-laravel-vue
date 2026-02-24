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
        Schema::create('hris_leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->integer('max_days');
            $table->boolean('is_paid')->default(true);
            $table->boolean('carry_over')->default(false);
            $table->integer('max_carry_over_days')->default(0);
            $table->enum('gender_specific', ['all', 'male', 'female'])->default('all');
            $table->boolean('requires_document')->default(false);
            $table->integer('min_working_days')->default(0)->comment('minimum masa kerja dalam hari');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('hris_leave_type_approval_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leave_type_id');
            $table->integer('step_order');
            $table->enum('approver_type', ['direct_svp', 'division_head', 'hr', 'specific_user']);
            $table->unsignedBigInteger('approver_user_id')->nullable()->comment('diisi jika approver_type = specific_user');
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();

            $table->foreign('leave_type_id')->references('id')->on('hris_leave_types')->cascadeOnDelete();
            $table->foreign('approver_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hris_leave_types');
        Schema::dropIfExists('hris_leave_type_approval_steps');
    }
};
