<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('event_log_id')->nullable();
            $table->integer('username')->nullable();
            $table->string('attempted_password')->nullable();
            $table->integer('request_id')->nullable();
            $table->string('filename')->nullable();
            $table->string('comment')->nullable();
            $table->text('changed_fields')->nullable();
            $table->string('ipaddress');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_log');
    }
}
