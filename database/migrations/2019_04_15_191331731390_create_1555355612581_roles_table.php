<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create1555355612581RolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
			$table->integer('group_id');
            $table->nullableTimestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
