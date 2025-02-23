<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvisorsTable extends Migration
{
    public function up()
    {
        Schema::create('advisors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number', 50)->nullable(); // Existing phone_number column
            $table->string('password');
            $table->string('metric_number')->unique();
            $table->unsignedInteger('max_students');
            $table->unsignedInteger('male_count')->default(0);
            $table->unsignedInteger('female_count')->default(0);
            $table->unsignedBigInteger('program_id')->nullable(); // Foreign key to programs table
            $table->string('profile_image')->nullable(); // Profile image column
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Add foreign key constraint to program_id
            $table->foreign('program_id')
                  ->references('id')
                  ->on('programs') // Ensure 'programs' table exists first
                  ->onDelete('set null') // If the program is deleted, set program_id to null
                  ->onUpdate('cascade'); // Cascade updates to the program_id
        });
    }

    public function down()
    {
        // Drop the advisors table if it exists
        Schema::dropIfExists('advisors');
    }
}