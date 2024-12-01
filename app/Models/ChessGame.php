<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChessGame extends Model
{

    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'player1_id',
        'player2_id',
        'player1_name',
        'player2_name',
        'result',
        'game_finish_fen',
    ];
}
