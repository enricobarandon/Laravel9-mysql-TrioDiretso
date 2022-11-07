<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Draw extends Model
{
    use HasFactory;

    protected $table = 'draws';

    protected $fillable = [
        'schedule_id',
        'draw_number',
        'status',
        'result'
    ];

    public static function controlBetting($scheduleId, $gameNum, $action)
    {
        $response = [
            'result'    => 0,
            'title'     => 'Error',
            'text'   => '',
            'icon'      => 'error'
        ];
        
        $drawInfo = Draw::where('schedule_id', $scheduleId)
                            // ->where('draw_number', $gameNum)
                            ->orderBy('id','desc')
                            ->first();
        switch($action) {

            case 'open':

                if ($drawInfo) {
                    // > 0
                    if ($drawInfo->status == 'open') {

                        $response['text'] = "Draw # $gameNum already opened";

                    } else if($drawInfo->status == 'closed') {

                        $response['text'] = "Draw # $gameNum already closed";

                    } else if($drawInfo->status == 'standby') {
                        Draw::where('schedule_id', $scheduleId)
                            ->where('draw_number', $gameNum)
                            ->update([
                                'status'    => 'open'
                            ]);
                        $response['title'] = 'Success';
                        $response['text'] = "Draw # $gameNum betting is now open.";
                        $response['icon'] = 'success';
                    }

                } else {
                    // Open first draw #
                    Draw::create([
                        'schedule_id'   => $scheduleId,
                        'draw_number'   => 1,
                        'status'        => 'open',
                        'result'        => ''
                    ]);
                }

                break;


            case 'close':

                if ($drawInfo->status == 'open') {
                    Draw::where('schedule_id', $scheduleId)
                            ->where('draw_number', $gameNum)
                            ->update([
                                'status'    => 'closed'
                            ]);

                    $response['result'] = 1;
                    $response['title'] = 'Success';
                    $response['text'] = "Draw # $gameNum closed.";
                    $response['icon'] = 'success';
                } else if ($drawInfo->status == 'closed') {
                    $response['text'] = "Draw # $gameNum already closed.";
                } else if ($drawInfo->status == 'standby') {
                    $response['text'] = "Draw # $gameNum still on standby.";
                }

                break;


            case 'next':

                if ($drawInfo->result != '' and $drawInfo->status == 'confirmed') {
                    // create next draw #
                    $nextDrawNum = $drawInfo->draw_number + 1;
                    Draw::create([
                        'schedule_id'   => $scheduleId,
                        'draw_number'   => $nextDrawNum,
                        'status'        => 'standby',
                        'result'        => ''
                    ]);
                    $response['result'] = 1;
                    $response['title'] = 'Success';
                    $response['text'] = 'Draw # ' . $nextDrawNum . ' on standby.';
                    $response['icon'] = 'success';
                } else {
                    $response['text']  = 'Please declare the result for draw # ' . $drawInfo->draw_number . ' before proceeding to the next draw #';
                }

                break;

        }

        return json_encode($response);
    }
}
