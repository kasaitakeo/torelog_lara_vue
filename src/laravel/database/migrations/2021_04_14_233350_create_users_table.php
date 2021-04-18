<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->comment('ユーザ名');
            $table->string('screen_name', 30)->unique()->comment('アカウント名');
            $table->string('email', 255)->unique()->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_image')->default('https://placehold.jp/50x50.png')->comment('プロフィール画像');
            $table->string('profile_text')->default('よろしくお願いします')->comment('プロフィール文');
            $table->rememberToken();
            $table->timestamps();

            $table->index('id');
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
}