<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Draw;
use Validator;
use Auth;
use DB;
use App\Models\PendingBet;

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

    public function declare()
    {
        $user = Auth::User();

        $first = request()->first;
        $second = request()->second;
        $third = request()->third;
        $cancel = request()->cancel;
        $drawNum = request()->drawNum;

        $response = [
            'result'        => 0,
            'title'         => 'Error',
            'text'          => '',
            'icon'          => 'error',
        ];

        $validator = Validator::make([
            'first'         =>  $first,
            'second'        =>  $second,
            'third'         =>  $third,
        ],[
            'first'         => ['required','numeric'],
            'second'        => ['required','numeric'],
            'third'         => ['required','numeric']
        ]);

        if ($validator->fails()) {
            $response['text'] = implode(",",$validator->messages()->all());
            return json_encode($response);
        }

        $ongoingGame = Schedule::where('status','active')->first();

        if (!$ongoingGame) {
            $response['text'] = 'Game not found.';
            return json_encode($response);
        }

        $drawInfo = Draw::where('schedule_id', $ongoingGame->id)->where('draw_number', $drawNum)->first();

        if (!$drawInfo) {
            $response['text'] = "Draw # $drawNum not found.";
            return json_encode($response);
        }

        DB::beginTransaction();

        $update = 0;

        $adminId = $drawInfo->f_admin_id == 0 ? 'f_admin_id' : 's_admin_id';
        $inputResult = $first .'-'. $second . '-' . $third;

        $updateForm = [
            $adminId => $user->id,
            'result' => $inputResult
        ];

        try {

            if ($drawInfo->f_admin_id != 0) {
                // check if the same admin declaration
                if ($drawInfo->f_admin_id == $user->id) {
                    $response['text'] = 'You already declared results for this draw #.';
                    return json_encode($response);
                }

                // compare first and second declarations
                if ($drawInfo->result == $inputResult) {
                    $updateForm['status'] = 'confirmed';
                } else {
                    // result mismatched
                    $response['text'] = 'Declared results mismatched.';
                    return json_encode($response);
                }
            }

            $update = Draw::where('schedule_id', $ongoingGame->id)
                            ->where('draw_number', $drawNum)
                            ->update($updateForm);

            

            if ($update) {
                $response['title'] = 'Success';
                $response['text'] = 'Result posted.';
                $response['result'] = 1;
                $response['icon'] = 'success';

                if ($updateForm['status'] == 'confirmed') {
                    // start grading pending bets
                    PendingBet::setWinners($ongoingGame->id, $drawNum, $inputResult, $ongoingGame->multiplier);
                    // update players balances

                    // transfer bets to past bets table
                    
                }

            } else {
                $response['text'] = 'Result declaration failed.';
            }

            DB::commit();

        } catch (Throwable $e) {

            DB::rollback();

            $response['text'] = 'Something went wrong while posting the bet.';

        }

        return json_encode($response);

    }

}
