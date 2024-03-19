<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    // Retrieves the game data and history for the current user
    public function index(Request $request)
    {
        $userId = auth()->id();
        $userService = new UserService();

        $game = $userService->getGame($userId);
        
        $history = $userService->getGameHistory($userId);

        return view('dashboard',compact('game','history'));;
    }

    // Retrieves all available roles for the game
    public function role()
    {
        $userService = new UserService();

        $roles = $userService->getRoles(); 

        return view('role',compact('roles'));
    }
}
