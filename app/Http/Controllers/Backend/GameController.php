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

        if ($schedule->status == 'active') {
    
            $drawInfo = Draw::where('schedule_id', $schedule->id)->orderBy('id','desc')->first();
            
            return view('backend.admin.declarator.manage', compact('gameInfo','drawInfo'));

        }

        return view('backend.admin.declarator.inactive');

    }

    public function control()
    {
        $ongoingGame = Schedule::where('status','active')->first();

        $drawNum = request()->drawNum;

        $action = request()->action;

        $control = Draw::controlBetting($ongoingGame->id, $drawNum, $action);

        return $control;
    }
}
