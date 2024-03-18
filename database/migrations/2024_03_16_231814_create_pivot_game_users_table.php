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
        Schema::create('pivot_game_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('game_id');
            $table->bigInteger('user_id');
            $table->integer('role_id');
            $table->boolean('is_alive')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_game_users');
    }
};
