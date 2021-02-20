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

Route::group(['prefix' => 'logs'], function () {
    // トレログ一覧
    Route::get('', 'LogsController@index')->name('log.index');

    // トレログ作成（投稿）
    Route::post('', 'LogsController@store')->name('log.store');

    // トレログ詳細
    Route::get('/{log}', 'LogsController@show')->name('log.show');

    // トレログ更新
    Route::put('/{log}', 'LogsController@update')->name('log.update');

    // トレログ削除
    Route::delete('/{log}', 'LogsController@destroy')->name('log.destroy');
});

Route::group(['prefix' => 'users'], function () {
    // ユーザー一覧
    Route::get('', 'UsersController@index')->name('user.index');

    // ユーザー詳細
    Route::get('/{user}', 'UsersController@show')->name('user.show');

    // ユーザー更新
    Route::put('/{user}', 'UsersController@update')->name('user.update');
    
    // フォロー
    Route::put('/{user}/follow', 'UsersController@follow')->name('user.follow');

    // フォロー解除
    Route::delete('/{user}/unfollow', 'UsersController@unfollow')->name('user.unfollow');
});

Route::group(['prefix' => 'events'], function () {
    // 種目一覧
    Route::get('', 'EventsController@index')->name('event.index');

    // 種目作成
    Route::post('', 'EventsController@store')->name('event.store');

    // 種目詳細
    Route::get('/{event}', 'EventsController@show')->name('event.show');

    // 種目更新
    Route::put('/{event}', 'EventsController@update')->name('event.update');

    // 種目削除
    Route::delete('/{event}', 'EventsController@destroy')->name('event.destroy');
});

// コメント作成
Route::post('/comments/{comment}', 'CommentsController@store')->name('comment.store');

// いいね
Route::put('favorites/{favorite}', 'FavoritesController@update')->name('favorite.update');

// いいね解除
Route::delete('favorites/{favorite}', 'FavoritesController@destroy')->name('favorite.destroy');