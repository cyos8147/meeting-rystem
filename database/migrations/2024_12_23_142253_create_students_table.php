<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('metric_number')->unique();
            $table->string('name');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('race', ['Malay', 'Chinese', 'Indian', 'Other'])->nullable(); // Adding race column
            $table->unsignedBigInteger('program_id')->nullable(); // Allow program_id to be nullable
            $table->unsignedInteger('semester');
            $table->unsignedBigInteger('advisor_id')->nullable(); // Allow advisor_id to be nullable
            $table->string('phone_number', 20)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('profile_image')->nullable(); // Profile image column
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Setting up relationships
            $table->foreign('advisor_id')->references('id')->on('advisors')
                  ->onDelete('set null')  // Set to null when the related advisor is deleted
                  ->onUpdate('cascade'); // Update if advisor id changes

            $table->foreign('program_id')->references('id')->on('programs')
                  ->onDelete('set null')  // Set to null when the related program is deleted
                  ->onUpdate('cascade'); // Update if program id changes
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
