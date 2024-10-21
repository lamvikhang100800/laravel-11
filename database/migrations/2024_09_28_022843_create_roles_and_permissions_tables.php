<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {   
        Schema::create('tbl_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->string('status');
            $table->timestamps();
        });


        Schema::create('tbl_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('tbl_user_has_roles', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('role_id');
            $table->timestamps();
            
            $table->primary(['user_id', 'role_id']);

        });

        Schema::create('tbl_role_has_permissions', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->timestamps();

            $table->primary(['role_id', 'permission_id']);

        });


    }

    public function down(): void
    {   
        Schema::dropIfExists('tbl_permissions');
        Schema::dropIfExists('tbl_roles');
        Schema::dropIfExists('tbl_user_has_roles');
        Schema::dropIfExists('tbl_role_has_permissions');
    }
};
