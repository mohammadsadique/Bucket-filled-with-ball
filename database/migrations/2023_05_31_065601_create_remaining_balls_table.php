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
        Schema::create('remaining_balls', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->nullable();	
            $table->unsignedBigInteger('ball_id')->nullable();	
            $table->integer('remaining_balls')->default(0);
            $table->timestamps();

            $table->foreign('ball_id')->references('id')->on('ball_lists')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remaining_balls');
    }
};
