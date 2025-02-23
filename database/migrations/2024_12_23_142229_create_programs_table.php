<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อของโปรแกรม เช่น Bachelor, Diploma
            $table->string('type'); // ประเภทของโปรแกรม เช่น Diploma หรือ Bachelor
            $table->timestamps(); // created_at และ updated_at
        }); 
    }

    public function down()
    {
        Schema::dropIfExists('programs');
    }
}
