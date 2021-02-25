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
            $table->unsignedInteger('event_id')->comment('イベントID');
            $table->integer('weight');
            $table->integer('rep');
            $table->integer('set');
            $table->timestamps();

            $table->foreign('log_id')
                ->references('id')
                ->on('logs')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('event_id')
                ->references('id')
                ->on('events')
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
