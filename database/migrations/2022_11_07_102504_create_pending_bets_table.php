<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_bets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('schedule_id');
            $table->integer('draw_number');
            $table->integer('user_id');
            $table->char('bet',10);
            $table->integer('bet_amount');
            $table->string('status')->default('pending');
            $table->decimal('payout',11,2)->default(0);
            $table->decimal('income',11,2)->default(0);
            $table->timestamps();
            $table->index(['schedule_id', 'draw_number', 'user_id']);
            $table->index(['schedule_id', 'draw_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_bets');
    }
};
