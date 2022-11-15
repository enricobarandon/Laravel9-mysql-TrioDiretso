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

            // PendingBet::where('schedule_id', $scheduleId)
            //             ->where('draw_number', $drawNum)
            //             ->where('status', 'pending')
            //             ->where('bet', '!=', $winResult)
            //             ->update([
            //                 'status'    => 'lost',
            //                 'payout'    => '0'
            //             ]);
        }
    }

    static function transferToPastBets($scheduleId, $drawNum) 
    {
        $fields = [
            
        ];
    }

    static function transferLostBets($scheduleId, $drawNum) 
    {
        try {
            $insertToPastBets = DB::insert(
                DB::raw(
                    "insert ignore into past_bets
                        select
                            null,
                            p.schedule_id,
                            p.draw_number,
                            p.user_id,
                            p.bet,
                            p.bet_amount,
                            'lost',
                            p.payout,
                            p.bet_amount,
                            p.created_at,
                            p.updated_at
                        from pending_bets as p
                    where p.schedule_id = $scheduleId
                        and p.draw_number = $drawNum
                        and p.status = 'pending'
                    "
                )
            );

            DB::table('pending_bets')->where('schedule_id', '=', $scheduleId)
                        ->where('draw_number', '=', $drawNum)
                        ->where('status', '=', 'pending')
                        ->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
        }

    }

    static function transferWinBets($scheduleId, $drawNum) 
    {
        try {
            $insertToPastBets = DB::insert(
                DB::raw(
                    "insert ignore into past_bets
                        select
                            null,
                            p.schedule_id,
                            p.draw_number,
                            p.user_id,
                            p.bet,
                            p.bet_amount,
                            p.status,
                            p.payout,
                            p.bet_amount,
                            p.created_at,
                            p.updated_at
                        from pending_bets as p
                    where p.schedule_id = $scheduleId
                        and p.draw_number = $drawNum
                        and p.status = 'win'
                    "
                )
            );

            DB::table('pending_bets')->where('schedule_id', '=', $scheduleId)
                        ->where('draw_number', '=', $drawNum)
                        ->where('status', '=', 'win')
                        ->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
        }

    }

    static function updatePlayerBalance($scheduleId, $drawNum) 
    {
        DB::table(DB::raw("
                    users as u,(
                        SELECT user_id, sum(payout) as payoutsum FROM pending_bets
                        WHERE 
                            schedule_id = ".intval($scheduleId)." and
                            draw_number = ".intval($drawNum)."
                            and status = 'win'
                        GROUP BY user_id
                    ) as b
                "))
                ->where('u.id', '=', DB::raw('b.user_id'))
                ->update([
                    'u.balance' => DB::raw('u.balance + b.payoutsum')
                ]);
    }
}
