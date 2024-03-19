<?php

namespace App\Services;

use App\Models\Game;
use App\Models\PivotGameRound;
use App\Models\PivotGameUser;
use App\Models\Role;
use App\Models\User;

class GameService
{
    // Retrieves the IDs of users marked as bots
    public function getBotId()
    {
        $getBotId = User::where('is_bot', 1)->pluck('id');

        return $getBotId;
    }

    // Retrieves the role ID based on the role name
    public function getRoleId($name)
    {
        $getRoleId = Role::where('name', $name)->firstOrFail();

        return $getRoleId;
    }

     // Creates a new game and assigns roles to players
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

    // Retrieves the players in the game for the night phase
    public function nightGame($game_id){

        $players = PivotGameUser::where('game_id', $game_id)->get();

        return $players;

    }

     // Updates the game status to indicate a player has left the game
    public function leaveGame($game_id)
    {
        $leaveGame = Game::findOrFail($game_id);
        $leaveGame->status_id = 2;
        $leaveGame->save();

        return 1;
    }

     // Handles killing a player during the day phase
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

        $votes = $this->getVotes($game_id,$round);
        
        return 1;
    }

     // Handles saving a player during the day phase
    public function savePlayerDay($game_id, $user_id_from,$user_id_to,$round,$role_id)
    {
        
        $pivot = PivotGameRound::create([
            'game_id' => $game_id,
            'user_id_from' => $user_id_from,
            'user_id_to' => $user_id_to,
            'round' => $round + 1,
            'round_time' => 'day',
            'save_player' => 1
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

        $votes = $this->getVotes($game_id,$round);
        
        return 1;
    }

    // Handles instant killing a player during the day phase
    public function instaKillPlayerDay($game_id, $user_id_from,$user_id_to,$round,$role_id)
    {
        
        $pivot = PivotGameRound::create([
            'game_id' => $game_id,
            'user_id_from' => $user_id_from,
            'user_id_to' => $user_id_to,
            'round' => $round + 1,
            'round_time' => 'day',
            'save_player' => 1
        ]);

        $killMafia = $this->killMafiaDay($game_id,$user_id_from, $user_id_to, $round);

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

        $votes = $this->getVotes($game_id,$round);
        
        return 1;
    }

    // Handles instant killing a player during the night phase
    public function instaKillPlayerNight($game_id, $user_id_from,$user_id_to,$round,$role_id)
    {
        
        $pivot = PivotGameRound::create([
            'game_id' => $game_id,
            'user_id_from' => $user_id_from,
            'user_id_to' => $user_id_to,
            'round' => $round + 1,
            'round_time' => 'night',
            'save_player' => 1
        ]);

        $this->killPlayer($game_id,$user_id_to);

        $pivotMafiaBot = PivotGameUser::where('game_id', $game_id)
                            ->where('is_alive', 1)
                            ->where('user_id','!=',$user_id_from)
                            ->where('role_id',1)
                            ->pluck('user_id');

        $pivotBotVote = PivotGameUser::where('game_id', $game_id)
                            ->where('is_alive', 1)
                            ->where('user_id','!=',$pivotMafiaBot[0])
                            ->where('user_id','!=' ,$user_id_from)
                            ->pluck('user_id');

        $pivot = PivotGameRound::create([
                'game_id' => $game_id,
                'user_id_from' => $pivotMafiaBot[0],
                'user_id_to' => $pivotBotVote->random(),
                'round' => $round + 1,
                'round_time' => 'night',
                'kill_player' => 1
            ]);
        
        $pivotMafiaKillBot = PivotGameRound::where('game_id', $game_id)->where('round',$round+1)->where('user_id_from',$pivotMafiaBot[0])->pluck('user_id_to');


        $this->killPlayer($game_id,$pivotMafiaKillBot[0]);
                
        return 1;
    }

    // Simulates players' actions during the night phase
    public function skipNight($game_id,$round,)
    {
        
        $pivotBot = PivotGameUser::where('game_id', $game_id)->where('is_alive', 1)->where('role_id',1)->pluck('user_id');

        foreach ($pivotBot as $bot) {

            $pivotBotVote = PivotGameUser::where('game_id', $game_id)->where('is_alive', 1)->where('user_id','!=',$bot)->pluck('user_id');


            $pivot = PivotGameRound::create([
                'game_id' => $game_id,
                'user_id_from' => $bot,
                'user_id_to' => $pivotBotVote->random(),
                'round' => $round + 1,
                'round_time' => 'night',
                'kill_player' => 1
            ]);

            $pivotMafiaKillBot = PivotGameRound::where('game_id', $game_id)->where('round',$round+1)->where('user_id_from',$bot)->pluck('user_id_to');

            $this->killPlayer($game_id,$pivotMafiaKillBot[0]);
        }
                
        return 1;
    }

    // Retrieves the current round of the game
    public function getRound($game_id)
    {
        
       $round = PivotGameRound::where('game_id', $game_id)->orderBy('round', 'desc')->first();

        return $round;
    }
    
    // Calculates and handles votes during the day phase
    public function getVotes($game_id,$round)
    {
        
        $votes = PivotGameRound::selectRaw('user_id_to, COUNT(kill_player) as kill_count')
                ->where('game_id', $game_id)
                ->where('round', $round+1)
                ->groupBy('user_id_to')
                ->orderBy('kill_count', 'desc')
                ->limit(2)
                ->get();

        if($votes[0]->kill_count > $votes[1]->kill_count) {

            $checkIfPlayerIsSave = PivotGameRound::where('game_id',$game_id)->where('round',$round+1)->where('user_id_to',$votes[0]->user_id_to )->pluck('save_player');

            if($checkIfPlayerIsSave[0] == 1) {
                return 1;
            }

            $this->killPlayer($game_id,$votes[0]->user_id_to);
        }

        return ;
    }

    // Handles killing a Mafia member during the day phase
    public function killMafiaDay($game_id,$user_id_from, $user_id_to, $round)
    {
        
        $checkIfPlayeIsMafia = PivotGameUser::where('game_id', $game_id)->where('user_id', $user_id_to)->pluck('role_id');

        if($checkIfPlayeIsMafia[0] == 1) {
            $this->killPlayer($game_id,$user_id_to);
        }else{
            $this->killPlayer($game_id,$user_id_from);
        }

        return 1;
    }

    // Checks if the game is over based on the alive status of players
    public function checkIfGameIsOver($game_id){

        $checkMafiaAlive = PivotGameUser::where('game_id', $game_id)->where('is_alive', 1)->where('role_id',1)->count();
        $checkOtherAlive = PivotGameUser::where('game_id', $game_id)->where('is_alive', 1)->where('role_id','!=',1)->count();

        if($checkMafiaAlive == $checkOtherAlive || $checkOtherAlive < $checkMafiaAlive ) {
            $this->endGame($game_id,0);
            return 0;
        }elseif($checkMafiaAlive == 0) {
            $this->endGame($game_id,1);
            return 1;
        }

        return 2;
    }

    // Marks a player as dead in the game
    public function killPlayer($game_id,$user_id)
    {
        $killSheriff = PivotGameUser::where('game_id', $game_id)->where('user_id', $user_id)->first();
        $killSheriff->is_alive = 0;
        $killSheriff->save();

        return 1;
    }

    // Marks the game as ended and determines the winner
    public function endGame($game_id,$winner)
    {
        $game = Game::findOrFail($game_id);
            $game->status_id = 2;
            $game->winner = $winner;
            $game->save();
            return $winner;
    }
    
}