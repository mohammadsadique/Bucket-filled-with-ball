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
        Schema::create('store_ball_in_buckets', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->nullable();	
            $table->unsignedBigInteger('bucket_id')->nullable();	
            $table->unsignedBigInteger('ball_id')->nullable();	
            $table->integer('total_ball')->default(0);
            $table->decimal('bucket_empty_space', 8, 2)->default(0);
            $table->decimal('ball_filled_space', 8, 2)->default(0)->comment('Bucket filled by balls');
            $table->decimal('remain_empty_space_in_bucket', 8, 2)->default(0);
            $table->integer('num_of_left_ball')->default(0);
            $table->timestamps();

            $table->foreign('bucket_id')->references('id')->on('bucket_lists')->onDelete('cascade');
            $table->foreign('ball_id')->references('id')->on('ball_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_ball_in_buckets');
    }
};
