<?php

namespace App\Http\Controllers;

use App\Models\PivotGameRound;
use App\Models\PivotGameUser;
use App\Services\GameService;
use App\Services\UserService;
use Illuminate\Http\Request;

class GameController extends Controller
{    
    protected $gameService;
    protected $userService;

    // Constructor to instantiate GameService and UserService
    public function __construct()
    {
        $this->gameService = new GameService();
        $this->userService = new UserService();
    }

    // Handles the actions during the day phase of the game
    public function gameDay()
    {
        $userId = auth()->id();

        $players = $this->gameService->createGame($userId)->shuffle();
        
        $userService = new UserService();
        $userGame = $userService->getGame($userId)->shuffle();

        $role = $userService->getRolesById($userGame[0]->role_id);

        $checkIfGameIsOver = $this->gameService->checkIfGameIsOver($userGame[0]->game_id);

        if ($checkIfGameIsOver == 2) {
            return view('game.day',compact('players', 'role','userId','userGame'));
        }else{
            return redirect()->route('dashboard');
        }
    }

    // Handles the actions during the night phase of the game
    public function gameNight()
    {
        $userId = auth()->id();
        
        $userGame = $this->userService->getGame($userId)->shuffle();

        $players = $this->gameService->nightGame($userGame[0]->game_id);

        $role = $this->userService->getRolesById($userGame[0]->role_id);       

        return view('game.night',compact('players', 'role','userId','userGame'));
    }

     // Handles a player leaving the game
    public function leaveGame(Request $request, $id)
    {

        $userId = auth()->id();
        $userId = auth()->id();

        $leaveGame = $this->gameService->leaveGame($id);

        $game = $this->userService->getGame($userId);

        $history = $this->userService->getGameHistory($userId);

        return view('dashboard',compact('game','history'))->with('successMsg','You have left the game');
    }

    // Handles killing a player during the day phase of the game
    public function killPlayerDay(Request $request, $id)
    {

        
        $userId = auth()->id();
     
        $userGame = $this->userService->getGame($userId)->shuffle();
        
        $round = $this->gameService->getRound($userGame[0]->game_id);

        if ($round == null) {
            $round = 0;
        }else{
            $round = $round->round;
        }
        
        $killPlayerDay = $this->gameService->killPlayerDay($userGame[0]->game_id,$userId,$id,$round,$userGame[0]->role_id);

        $game = $this->userService->getGame($userId);

        $role = $this->userService->getRolesById($userGame[0]->role_id);

        $players = $this->gameService->nightGame($userGame[0]->game_id);

        return redirect()->route('night.game', compact('players', 'role', 'userId', 'userGame'));
    }

    // Handles saving a player during the day phase of the game
    public function savePlayerDay(Request $request, $id)
    {
        $userId = auth()->id();
        
        $userGame = $this->userService->getGame($userId)->shuffle();
        
        $round = $this->gameService->getRound($userGame[0]->game_id);

        if ($round == null) {
            $round = 0;
        }else{
            $round = $round->round;
        } 
        
        $savePlayerDay = $this->gameService->savePlayerDay($userGame[0]->game_id,$userId,$id,$round,$userGame[0]->role_id);

        $game = $this->userService->getGame($userId);

        $role = $this->userService->getRolesById($userGame[0]->role_id);

        $players = $this->gameService->nightGame($userGame[0]->game_id);

        return redirect()->route('night.game', compact('players', 'role', 'userId', 'userGame'));
    }
    
    // Handles instantly killing a player during the day phase of the game
    public function instaKillPlayerDay(Request $request, $id)
    {

        $userId = auth()->id();
        
        $userGame = $this->userService->getGame($userId)->shuffle();
        
        $round = $this->gameService->getRound($userGame[0]->game_id);

        if ($round == null) {
            $round = 0;
        }else{
            $round = $round->round;
        } 
        
        $instaKillPlayerDay = $this->gameService->instaKillPlayerDay($userGame[0]->game_id,$userId,$id,$round,$userGame[0]->role_id);

        $game = $this->userService->getGame($userId);

        $role = $this->userService->getRolesById($userGame[0]->role_id);

        $players = $this->gameService->nightGame($userGame[0]->game_id);

        return redirect()->route('night.game', compact('players', 'role', 'userId', 'userGame'));
    }

     // Handles instantly killing a player during the night phase
    public function instaKillPlayerNight(Request $request, $id)
    {

        $userId = auth()->id();
        
        $userGame = $this->userService->getGame($userId)->shuffle();
        
        $round = $this->gameService->getRound($userGame[0]->game_id);

        if ($round == null) {
            $round = 0;
        }else{
            $round = $round->round;
        } 
        
        $instaKillPlayerNight = $this->gameService->instaKillPlayerNight($userGame[0]->game_id,$userId,$id,$round,$userGame[0]->role_id);

        $game = $this->userService->getGame($userId);

        $role = $this->userService->getRolesById($userGame[0]->role_id);

        $players = $this->gameService->nightGame($userGame[0]->game_id);

        return redirect()->route('create.game', compact('players', 'role', 'userId', 'userGame'));
    }

    // Skips the night phase of the game
    public function skipNight(Request $request)
    {
       
        $userId = auth()->id();;
        
        $userGame = $this->userService->getGame($userId)->shuffle();
        
        $round = $this->gameService->getRound($userGame[0]->game_id);

        if ($round == null) {
            $round = 0;
        }else{
            $round = $round->round;
        } 
        
        $skipNight = $this->gameService->skipNight($userGame[0]->game_id,$round);

        $game = $this->userService->getGame($userId);

        $role = $this->userService->getRolesById($userGame[0]->role_id);

        $players = $this->gameService->nightGame($userGame[0]->game_id);

        return redirect()->route('create.game');

    }
}
