<?php

namespace App\Services;

use App\Models\Game;
use App\Models\PivotGameUser;
use App\Models\Role;
use App\Models\User;

class UserService
{

    public function getGame($user_id){

        $games = User::select('*')
                        ->leftJoin('pivot_game_users as pgu', 'users.id', '=', 'pgu.user_id')
                        ->leftJoin('games as g', 'pgu.game_id', '=', 'g.id')
                        ->where('users.id', $user_id)
                        ->where('g.status_id', 1)
                        ->get();

        return $games;

    }

    public function getGameHistory($user_id){

        $history = Game::with(['status','pivotGameUser'])->where('status_id',2)->orderBy('id','desc')->limit(10)->get();

        return $history;

    }

    public function getRoles(){

        $roles = Role::all();

        return $roles;

    }
    
    public function getRolesById($id){

        $role = Role::where('id', $id)->firstOrFail();

        return $role;

    }

}