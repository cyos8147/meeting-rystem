<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('advisor_id');
            $table->date('meeting_date');
            $table->text('discussion_content');
            $table->json('evidence')->nullable(); // เปลี่ยนเป็น JSON
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('advisor_comment')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('advisor_id')->references('id')->on('advisors')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
