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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->nullable();
            // $table->integer('group_id');
            $table->boolean('is_active')->default(1);
            $table->integer('user_type_id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('contact', 50)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username');
            $table->string('password');
            $table->decimal('balance',11,2);
            $table->char('pin', 6)->nullable();
            $table->integer('max_bet')->default(1000);
            $table->text('session_id');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
