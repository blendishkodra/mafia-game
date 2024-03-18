<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PivotGameUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'user_id',
        'role_id',
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
