<?php

namespace App\Services;

use App\Models\Game;
use App\Models\PivotGameRound;
use App\Models\PivotGameUser;
use App\Models\Role;
use App\Models\User;

class GameService
{

    public function getBotId()
    {
        $getBotId = User::where('is_bot', 1)->pluck('id');

        return $getBotId;
    }

    public function getRoleId($name)
    {
        $getRoleId = Role::where('name', $name)->firstOrFail();

        return $getRoleId;
    }

    public function createGame($user_id)
    {

        $roles = [
            'Mafia' => 2,
            'Sheriff' => 1,
            'Mayor' => 1,
            'Doctor' => 1,
            'Villager' => 5,
        ];

        $activeGame = new UserService();
        $game = $activeGame->getGame($user_id);




        // Create a new game

        if($game->count() == 0) {

            $createGame = Game::create();

            $getBotId = $this->getBotId();
            $getBotId[10] = $user_id;
            

            $availableUserIds = $getBotId->toArray(); 
            shuffle($availableUserIds);

            foreach ($roles as $roleName => $count) {
                $getRoleId = $this->getRoleId($roleName);
                
                for ($i = 0; $i < $count; $i++) {
                    
                    if (empty($availableUserIds)) {
                        break; 
                    }
                    
                    $randomUser = array_pop($availableUserIds);
                    
                    // Create a new user
                    $pivot = PivotGameUser::create([
                        'game_id' => $createGame->id,
                        'user_id' => $randomUser,
                        'role_id' => $getRoleId->id
                    ]);
                }
            }
            
            $getCreatedGame = PivotGameUser::where('game_id', $createGame->id)->get();

            return $getCreatedGame;
        }

        $getCreatedGame = PivotGameUser::with('user')->where('game_id', $game[0]->game_id)->get();

        return $getCreatedGame;
        
    }

    public function nightGame($game_id){

        $players = PivotGameUser::where('game_id', $game_id)->get();

        return $players;

    }

    public function leaveGame($game_id)
    {
        $leaveGame = Game::findOrFail($game_id);
        $leaveGame->status = 2;
        $leaveGame->save();

        return 1;
    }

    public function killPlayerDay($game_id, $user_id_from,$user_id_to,$round,$role_id)
    {
        
        $pivot = PivotGameRound::create([
            'game_id' => $game_id,
            'user_id_from' => $user_id_from,
            'user_id_to' => $user_id_to,
            'round' => $round + 1,
            'round_time' => 'day',
            'kill_player' => ($role_id == 3) ? 2 : 1
        ]);

        $pivotBot = PivotGameUser::where('game_id', $game_id)->where('is_alive', 1)->where('user_id','!=',$user_id_from)->pluck('user_id');

        foreach ($pivotBot as $bot) {

            $pivotBotVote = PivotGameUser::where('game_id', $game_id)->where('is_alive', 1)->where('user_id','!=',$bot)->pluck('user_id');

            $pivotBotRole = PivotGameUser::where('game_id', $game_id)->where('user_id', $bot)->pluck('role_id');

            $pivot = PivotGameRound::create([
                'game_id' => $game_id,
                'user_id_from' => $bot,
                'user_id_to' => $pivotBotVote->random(),
                'round' => $round + 1,
                'round_time' => 'day',
                'kill_player' => ($pivotBotRole[0] == 3) ? 2 : 1
            ]);
        }

        $votes = $this->getVotes($game_id);
        
        return 1;
    }

    public function getRound($game_id)
    {
        
       $round = PivotGameRound::where('game_id', $game_id)->orderBy('round', 'desc')->first();

        return $round;
    }
    public function getVotes($game_id)
    {
        
        $votes = PivotGameRound::selectRaw('user_id_to, COUNT(kill_player) as kill_count')
                ->where('game_id', $game_id)
                ->groupBy('user_id_to')
                ->orderBy('kill_count', 'desc')
                ->limit(2)
                ->get();

        if($votes[0]->kill_count > $votes[1]->kill_count) {
            $killPlayer = PivotGameUser::where('game_id', $game_id)->where('user_id', $votes[0]->user_id_to)->first();
            $killPlayer->is_alive = 0;
            $killPlayer->save();
        }

        return 0;
    }
    
}