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
        Schema::create('appointments', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('department_id');
    $table->unsignedBigInteger('staff_id');
    $table->string('purpose_of_appointment');
    $table->date('appointment_date');
$table->time('appointment_time');
 $table->string('status')->default('pending');
  $table->boolean('rated')->default(false);
    $table->timestamps();

    // Foreign keys (optional, but recommended)
    $table->foreign('department_id')->references('user_id')->on('departments')->onDelete('cascade');
   $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

    $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
