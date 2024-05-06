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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('positionX', 10)->unique();
            $table->string('positionY', 50)->unique();
            $table->string('start_frame')->default(0);
            $table->string('flagX')->default(0);
            $table->string('flagY')->default(0);
            $table->string('flag_image')->default(0);
            $table->string('sentence')->default(0);
            $table->string('back_image')->default(0);
            $table->string('box_color')->default(0);
            $table->string('part')->default(0);
            $table->string('block_limit')->default(0);
            $table->string('toolbox')->default(0);
            $table->string('blocks_define')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
