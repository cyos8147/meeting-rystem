<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('email')->unique();
            $table->rememberToken();  // Add remember_token column
            $table->timestamps();  // Automatically adds created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
