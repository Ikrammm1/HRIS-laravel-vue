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
        Schema::create('hris_menu_auth_groups', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('menu_id');

            $table->foreign('group_id')
                ->references('id')
                ->on('hris_authorization_group')
                ->onDelete('cascade');

            $table->foreign('menu_id')
                ->references('id')
                ->on('hris_menus')
                ->onDelete('cascade');

            $table->timestamps();

            // optional tapi best practice
            $table->unique(['group_id', 'menu_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hris_menu_auth_groups');
    }
};
