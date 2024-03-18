<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PivotGameRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id_from',
        'user_id_to',
        'round',
        'round_time',
        'kill_player',
        'save_player'
    ];

}
