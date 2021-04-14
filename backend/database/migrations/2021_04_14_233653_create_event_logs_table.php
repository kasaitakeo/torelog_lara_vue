<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('log_id')->comment('ログID');
            $table->unsignedInteger('event_id')->comment('種目ID');
            $table->integer('weight')->comment('重量');
            $table->integer('rep')->comment('回数');
            $table->integer('set')->comment('セット数');
            $table->timestamps();

            $table->index('id');
            $table->index('log_id');
            $table->index('event_id');

            $table->foreign('log_id')
                ->references('id')
                ->on('logs')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_logs');
    }
}
