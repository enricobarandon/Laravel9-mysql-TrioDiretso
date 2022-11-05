<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Schedule;

class PlayerController extends Controller
{
    public function index() 
    {
        $user = Auth::User();

        $ongoingGame = Schedule::where('status','active')->first();

        if ($ongoingGame) {

            return view('backend.player.index', compact('user'));
            
        }

        $upcomingGames = Schedule::where('status','inactive')->get();

        return view('backend.player.noGame', compact('upcomingGames'));

    }
}
