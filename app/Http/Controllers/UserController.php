<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    protected $userService;

    // Constructor to instantiate GameService and UserService
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Retrieves the game data and history for the current user
    public function index(Request $request)
    {
        $userId = auth()->id();

        $game = $this->userService->getGame($userId);
        
        $history = $this->userService->getGameHistory($userId);

        return view('dashboard',compact('game','history'));;
    }

    // Retrieves all available roles for the game
    public function role()
    {
        $roles = $this->userService->getRoles(); 

        return view('role',compact('roles'));
    }
}
