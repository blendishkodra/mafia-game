<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard',  [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //Role explaination route
    Route::get('/role', [UserController::class, 'role'])->name('role');

    //Game explaination route
    Route::get('/game/day', [GameController::class, 'gameDay'])->name('create.game');
    Route::get('/game/night', [GameController::class, 'gameNight'])->name('night.game');
    Route::post('/game/leave/{id}', [GameController::class, 'leaveGame'])->name('leave.game');
    Route::post('/game/kill/{user_id}', [GameController::class, 'killPlayerDay'])->name('kill.player.day');
    Route::post('/game/insta-kill/{user_id}', [GameController::class, 'instaKillPlayerDay'])->name('insta.kill.player.day');
    Route::post('/game/save/{user_id}', [GameController::class, 'savePlayerDay'])->name('save.player.day');

    Route::post('/game/insta-kill/{user_id}/night', [GameController::class, 'instaKillPlayerNight'])->name('insta.kill.player.night');

    
});

require __DIR__.'/auth.php';
