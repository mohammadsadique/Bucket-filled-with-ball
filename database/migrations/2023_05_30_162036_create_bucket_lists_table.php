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
        Schema::create('bucket_lists', function (Blueprint $table) {
            $table->id();
            $table->string('bucketname');
            $table->integer('bucketvolume');
            $table->decimal('bucket_remaining_volume', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bucket_lists');
    }
};
