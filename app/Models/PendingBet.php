<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\User;
use Auth;

class PendingBet extends Model
{
    use HasFactory;

    protected $table = 'pending_bets';

    protected $fillable = [
        'schedule_id',
        'draw_number',
        'user_id',
        'bet',
        'bet_amount'
    ];

    static function setWinners($scheduleId, $drawNum, $winResult, $multiplier)
    {
        if ($winResult == 'cancelled') {

        } else {
            PendingBet::where('schedule_id', $scheduleId)
                        ->where('draw_number', $drawNum)
                        ->where('status', 'pending')
                        ->where('bet', $winResult)
                        ->update([
                            'status'    => 'win',
                            'payout'    => DB::raw('bet_amount * ' . $multiplier)
                        ]);

            PendingBet::where('schedule_id', $scheduleId)
                        ->where('draw_number', $drawNum)
                        ->where('status', 'pending')
                        ->where('bet', '!=', $winResult)
                        ->update([
                            'status'    => 'lost',
                            'payout'    => '0'
                        ]);
        }
    }

}
