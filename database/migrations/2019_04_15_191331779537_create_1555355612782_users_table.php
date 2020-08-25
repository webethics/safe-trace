<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create1555355612782UsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
			$table->string('first_name');
            $table->string('last_name')->nullable();
			$table->integer('role_id');
			$table->integer('created_by')->nullable();
            $table->string('email');
            $table->string('mobile_number')->nullable();
            $table->string('code_number')->nullable();
            $table->string('otp')->nullable();
            $table->datetime('email_verified_at')->nullable();
            $table->string('verify_token')->nullable();
            $table->string('password');
            $table->integer('status')->nullable();
            $table->string('remember_token')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
