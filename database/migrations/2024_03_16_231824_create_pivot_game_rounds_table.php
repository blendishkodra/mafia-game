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
        Schema::create('pivot_game_rounds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('game_id');
            $table->bigInteger('user_id_from');
            $table->bigInteger('user_id_to');
            $table->integer('round');
            $table->string('round_time');
            $table->integer('kill_player')->nullable();
            $table->integer('save_player')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_game_rounds');
    }
};
