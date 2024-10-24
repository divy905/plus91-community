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
            $table->string('name')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('mobile_number')->unique()->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expiration')->nullable();
            $table->string('city')->nullable();
            $table->date('dob')->nullable();
            $table->integer('exam')->default(0);
            $table->tinyInteger('auth_medium')->default(0)->comment('0->WEB, 1->MOBILE');
            $table->tinyInteger('active')->default(1)->comment('1->Active, 0->Not Active, 2->Suspended');
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
