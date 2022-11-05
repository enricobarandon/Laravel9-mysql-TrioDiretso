<?php
use App\Http\Controllers\Backend\ScheduleController;

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::middleware('role:Administrator')->group(function(){
        Route::resource('schedule', ScheduleController::class);
    });
});