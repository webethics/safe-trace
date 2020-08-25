<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
           
            $table->string('case_number');
            $table->integer('requested_user_id');
            $table->integer('assigned_user_id')->nullable();
            $table->integer('assignedBy')->nullable();
            $table->string('name');
            $table->string('company');
            $table->string('url');
            $table->text('other_info')->nullable();
            $table->string('priority');
            $table->string('data_archive');
            $table->text('social_media');
            $table->tinyInteger('status')->comment('1->new request,2->progess,3->completed,4->reopened');
			$table->integer('status_changed_by')->nullable();;
			$table->timestamps();
            $table->timestamp('completed_at')->nullable();
			$table->softDeletes();
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
