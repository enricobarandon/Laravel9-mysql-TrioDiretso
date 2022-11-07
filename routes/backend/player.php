<?php
use App\Http\Controllers\Backend\PlayerController;

// Route::group(['prefix' => 'admin'], function() {
    Route::middleware(['role:Administrator,Player', 'middleware' => 'auth'])->group(function(){
        Route::get('/', [PlayerController::class,           'index'])->name('player.index');
        Route::post('postBet', [PlayerController::class,    'postBet'])->name('player.postBet');
    });
// });