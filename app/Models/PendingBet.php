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

}
