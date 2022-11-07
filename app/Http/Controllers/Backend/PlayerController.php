<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Schedule;
use App\Models\Draw;
use Validator;
use App\Models\PendingBet;
use DB;

class PlayerController extends Controller
{
    public function index() 
    {
        $user = Auth::User();

        $ongoingGame = Schedule::where('status','active')->first();

        $drawInfo = '';

        if ($ongoingGame) {

            $drawInfo = Draw::where('schedule_id', $ongoingGame->id)->orderBy('id','desc')->first();

            return view('backend.player.index', compact('user','ongoingGame','drawInfo'));
            
        }

        $upcomingGames = Schedule::where('status','inactive')->get();

        return view('backend.player.noGame', compact('upcomingGames'));

    }

    public function postBet(Request $request)
    {
        $response = [
            'result'        => 0,
            'title'         => 'Error',
            'text'          => '',
            'icon'          => 'error',
            'new_balance'   => 0
        ];

        $user = Auth::user();
        
        $form = [
            'user_id'        => $user->id,
            'bet_amount'     => request()->betAmount,
            'draw_number'    => request()->drawNumber,
            'bet'            => request()->first . "-" . request()->second . "-" . request()->third,
        ];
        
        $ongoingGame = Schedule::where('status','active')->first();

        if ($ongoingGame) {
            $form['schedule_id'] = $ongoingGame->id;
        }

        $validator = Validator::make($form,[
            'bet_amount'     => ['required','numeric'],
            'draw_number'    => ['required','numeric'],
            'bet'            => ['required']
        ]);

        if ($validator->fails()) {
            $response['text'] = implode(",",$validator->messages()->all());
        }

        $drawInfo = Draw::where('schedule_id', $ongoingGame->id)->where('draw_number', $form['draw_number'])->first();

        if (!$drawInfo) {
            $response['text'] = 'No current draw.';
            return json_encode($response);
        }

        if ($drawInfo->status != 'open') {
            $response['text'] = 'Betting on draw # ' . $form['draw_number'] . ' is closed.';
            return json_encode($response);
        }

        if ($user->balance < $form['bet_amount']) {
            $response['text'] = 'Current balance is insufficient.';
            return json_encode($response);
        }

        DB::beginTransaction();

        $insert = 0;

        try {

            $user->balance -= $form['bet_amount'];

            $user->save();

            $insert = PendingBet::create($form);

            DB::commit();

        } catch (Throwable $e) {

            DB::rollback();

            $response['text'] = 'Something went wrong while posting the bet.';

        }

        if ($insert) {
            $response['title'] = 'Success';
            $response['text'] = 'Bet successfully posted.';
            $response['result'] = 1;
            $response['icon'] = 'success';
            $response['new_balance'] = number_format($user->balance,2);
        } else {
            $response['text'] = 'Bet posting failed.';
        }

        return json_encode($response);

    }
}
