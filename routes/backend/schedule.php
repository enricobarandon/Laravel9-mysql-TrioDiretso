<?php
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\GameController;

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::middleware('role:Administrator')->group(function(){
        Route::resource('schedule', ScheduleController::class, ['except' => [ 'show' ]]);
    });

    Route::middleware('role:Administrator,Declarator')->group(function(){
        Route::get('schedule/{schedule}/manage',        [GameController::class, 'index']);
        Route::post('schedule/controlBetting',          [GameController::class, 'control']);
    });

});