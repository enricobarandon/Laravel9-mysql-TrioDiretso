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
        $drawInfo = Draw::where('schedule_id', $scheduleId)
                            // ->where('draw_number', $gameNum)
                            ->orderBy('id','desc')
                            ->first();
        switch($action) {

            case 'open':

                if ($drawInfo) {
                    // > 0
                    dd($drawInfo);
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


                break;


            case 'next':

                if ($drawInfo->result == '' and $drawInfo->status == 'confirmed') {
                    // create next draw #
                    Draw::create([
                        'schedule_id'   => $scheduleId,
                        'draw_number'   => $drawInfo->draw_number++,
                        'status'        => 'standby',
                        'result'        => ''
                    ]);
                }

                break;

        }
    }
}
