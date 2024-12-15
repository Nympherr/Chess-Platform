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
        Schema::create('chess_games', function (Blueprint $table) {
            $table->id();
            $table->string('player1_id');
            $table->string('player2_id');
            $table->string('player1_name');
            $table->string('player2_name');
            $table->string('result');
            $table->string('game_finish_fen');
            $table->string('game_history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chess_games');
    }
};
