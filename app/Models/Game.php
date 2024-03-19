<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'winner'
    ];

    public function status()
    {
        return $this->hasOne(GameStatusDef::class, 'id', 'status_id');
    }

    public function pivotGameUser()
    {
        return $this->hasOne(PivotGameUser::class, 'game_id', 'id')->where('user_id', auth()->user()->id);
    }

}
