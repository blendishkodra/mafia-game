<?php

namespace App\Http\Controllers;

use App\Models\PivotGameRound;
use App\Models\PivotGameUser;
use App\Services\GameService;
use App\Services\UserService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function gameDay()
    {
        $userId = auth()->id();

        $gameService = new GameService();

        $players = $gameService->createGame($userId)->shuffle();
        
        $userService = new UserService();
        $userGame = $userService->getGame($userId)->shuffle();

        $role = $userService->getRolesById($userGame[0]->role_id);

        return view('game.day',compact('players', 'role','userId','userGame'));
    }

    public function gameNight()
    {
        $userId = auth()->id();
        $gameService = new GameService();
        $userService = new UserService();
        
        $userGame = $userService->getGame($userId)->shuffle();

        $players = $gameService->nightGame($userGame[0]->game_id);

        $role = $userService->getRolesById($userGame[0]->role_id);       

        return view('game.night',compact('players', 'role','userId','userGame'));
    }

    public function leaveGame(Request $request, $id)
    {

        $userId = auth()->id();
        // dd($id);
        $userId = auth()->id();

        $gameService = new GameService();
        $leaveGame = $gameService->leaveGame($id);

        
        $userService = new UserService();
        $game = $userService->getGame($userId);

        return view('dashboard',compact('game'))->with('successMsg','You have left the game');
    }

    public function killPlayerDay(Request $request, $id)
    {

        
        $userId = auth()->id();
     
        $userService = new UserService();
        $gameService = new GameService();

        $userGame = $userService->getGame($userId)->shuffle();
        
        $round = $gameService->getRound($userGame[0]->game_id);

        if ($round == null) {
            $round = 0;
        }else{
            $round = $round->round;
        }
        
        $killPlayerDay = $gameService->killPlayerDay($userGame[0]->game_id,$userId,$id,$round,$userGame[0]->role_id);

        $game = $userService->getGame($userId);

        $role = $userService->getRolesById($userGame[0]->role_id);

        $players = $gameService->nightGame($userGame[0]->game_id);

        return redirect()->route('night.game', compact('players', 'role', 'userId', 'userGame'));


        // return view('game.night',compact('players', 'role','userId','userGame'));
    }

    public function savePlayerDay(Request $request, $id)
    {

        $userId = auth()->id();
     
        $userService = new UserService();
        $gameService = new GameService();
        
        $userGame = $userService->getGame($userId)->shuffle();
        
        $round = $gameService->getRound($userGame[0]->game_id);

        if ($round == null) {
            $round = 0;
        }else{
            $round = $round->round;
        } 
        
        $savePlayerDay = $gameService->savePlayerDay($userGame[0]->game_id,$userId,$id,$round,$userGame[0]->role_id);

        $game = $userService->getGame($userId);

        $role = $userService->getRolesById($userGame[0]->role_id);

        $players = $gameService->nightGame($userGame[0]->game_id);

        return redirect()->route('night.game', compact('players', 'role', 'userId', 'userGame'));


        // return view('game.night',compact('players', 'role','userId','userGame'));
    }
    
    public function instaKillPlayerDay(Request $request, $id)
    {

        $userId = auth()->id();
     
        $userService = new UserService();
        $gameService = new GameService();
        
        $userGame = $userService->getGame($userId)->shuffle();
        
        $round = $gameService->getRound($userGame[0]->game_id);

        if ($round == null) {
            $round = 0;
        }else{
            $round = $round->round;
        } 
        
        $instaKillPlayerDay = $gameService->instaKillPlayerDay($userGame[0]->game_id,$userId,$id,$round,$userGame[0]->role_id);

        $game = $userService->getGame($userId);

        $role = $userService->getRolesById($userGame[0]->role_id);

        $players = $gameService->nightGame($userGame[0]->game_id);

        return redirect()->route('night.game', compact('players', 'role', 'userId', 'userGame'));


        // return view('game.night',compact('players', 'role','userId','userGame'));
    }

    public function instaKillPlayerNight(Request $request, $id)
    {

        $userId = auth()->id();
     
        $userService = new UserService();
        $gameService = new GameService();
        
        $userGame = $userService->getGame($userId)->shuffle();
        
        $round = $gameService->getRound($userGame[0]->game_id);

        if ($round == null) {
            $round = 0;
        }else{
            $round = $round->round;
        } 
        
        $instaKillPlayerNight = $gameService->instaKillPlayerNight($userGame[0]->game_id,$userId,$id,$round,$userGame[0]->role_id);

        $game = $userService->getGame($userId);

        $role = $userService->getRolesById($userGame[0]->role_id);

        $players = $gameService->nightGame($userGame[0]->game_id);

        return redirect()->route('create.game', compact('players', 'role', 'userId', 'userGame'));


        // return view('game.night',compact('players', 'role','userId','userGame'));
    }
}
