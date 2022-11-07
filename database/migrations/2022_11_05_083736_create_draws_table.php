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
        Schema::create('draws', function (Blueprint $table) {
            $table->id();
            $table->integer('schedule_id');
            $table->integer('draw_number');
            $table->integer('f_admin_id')->default(0);
            $table->integer('s_admin_id')->default(0);
            $table->string('status')->default('standby');
            $table->string('result',55);
            $table->timestamps();
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
        Schema::dropIfExists('draws');
    }
};
