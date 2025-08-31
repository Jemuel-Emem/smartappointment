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
        Schema::create('staff__ratings', function (Blueprint $table) {
            $table->id();
              $table->unsignedBigInteger('staff_id');
        $table->unsignedBigInteger('user_id');
        $table->tinyInteger('rating');
         $table->text('comment')->nullable();
        $table->timestamps();

        $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff__ratings');
    }
};
