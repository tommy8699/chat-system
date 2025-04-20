<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;

// AUTHENTIFIKÁCIA
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('auth.logout');
});

// CHRÁNENÉ RUTY PRE PRIHLÁSENÝCH
Route::middleware('auth:sanctum')->group(function () {

    // CHATY
    Route::prefix('chats')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chats.index');            // Všetky chaty používateľa
        Route::post('/', [ChatController::class, 'store'])->name('chats.store');            // Vytvoriť nový chat
        Route::get('/{chat}', [ChatController::class, 'show'])->name('chats.show');         // Detail chatu
        Route::put('/{chat}', [ChatController::class, 'update'])->name('chats.update');     // Premenovať chat
        Route::delete('/{chat}/leave', [ChatController::class, 'leave'])->name('chats.leave'); // Opustiť chat
        Route::post('/{chat}/invite', [ChatController::class, 'invite'])->name('chats.invite'); // Pozvať používateľa
        Route::delete('/{chat}/users/{user}', [ChatController::class, 'removeUser'])->name('chats.removeUser'); // Odstrániť používateľa
        Route::get('/{chat}/users', [ChatController::class, 'getUsers'])->name('chats.getUsers'); // Získať používateľov v chate
    });

    // SPRÁVY V CHATOCH
    Route::prefix('chats/{chat}/messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index');       // Všetky správy v chate
        Route::post('/', [MessageController::class, 'store'])->name('messages.store');      // Odoslať správu do chatu
    });

    // Odpoveď na konkrétnu správu
    Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');

    // Jednoduchý endpoint pre POST (napr. pre testovanie mimo /chats/{chat})
    Route::post('/messages', [MessageController::class, 'storeFlat'])->name('messages.storeFlat');
});
