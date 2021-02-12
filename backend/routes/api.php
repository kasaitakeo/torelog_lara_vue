<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ログインユーザー
Route::get('/user', function () {
    return Auth::user();
})->name('user');

// 'prefix' => 'contactでフォルダを指定することができ、頭につくcontact省略できる。'middleware' => 'auth'で認証機能 コールバックファンクションに通常のルーティングかく
// Route::group(['prefix' => 'contact', 'middleware' => 'auth'], function () {} )

Route::group(['prefix' => 'logs', 'middleware' => 'auth'], function () {
    // トレログ一覧
    Route::get('index', 'LogsController@index')->name('log.index');

    // トレログ作成（投稿）
    Route::post('create', 'LogsController@create')->name('log.create');

    // トレログ詳細
    Route::get('show/{id}', 'LogsController@show')->name('log.show');

    // トレログ更新
    Route::post('update/{id}', 'LogsController@update')->name('log.update');

    // トレログ削除
    Route::post('destroy/{id}', 'LogsController@destroy')->name('log.destroy');
});