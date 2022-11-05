<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Draw;

class GameController extends Controller
{
    public function index(Schedule $schedule)
    {
        $gameInfo = $schedule;

        $drawInfo = Draw::where('schedule_id', $schedule->id)->first();
        
        return view('backend.admin.declarator.manage', compact('gameInfo','drawInfo'));
    }

    public function control()
    {
        $ongoingGame = Schedule::where('status','active')->first();

        $drawNum = request()->drawNum;

        $action = request()->action;

        Draw::controlBetting($ongoingGame->id, $drawNum, $action);

        return $action;
    }
}
