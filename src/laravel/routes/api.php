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

Route::group(['prefix' => 'logs'], function () {
    // ログ一覧
    Route::get('', 'LogsController@index')->name('log.index');

    
    // ログ作成（投稿）
    Route::post('', 'LogsController@store')->name('log.store');
    
    // ログ詳細
    Route::get('/{log}', 'LogsController@show')->name('log.show');
    
    // ログ更新
    Route::put('/{log}', 'LogsController@update')->name('log.update');
    
    // ログ削除
    Route::delete('/{log}', 'LogsController@destroy')->name('log.destroy');
});

// 指定したユーザーのログ一覧
Route::get('users/{user_id}/logs', 'LogsController@userLog')->name('user_log');

Route::group(['prefix' => 'users'], function () {
    // ユーザー一覧
    Route::get('', 'UsersController@index')->name('user.index');

    // ユーザー詳細
    Route::get('/{user}', 'UsersController@show')->name('user.show');

    // ユーザー更新
    Route::put('', 'UsersController@update')->name('user.update');
    
    // フォロー
    Route::post('/follow/{user}', 'UsersController@follow')->name('user.follow');

    // フォロー解除
    Route::post('/unfollow/{user}', 'UsersController@unfollow')->name('user.unfollow');
});

// 指定したユーザーidの種目の取得
Route::get('/users/{user}/events', 'EventsController@userEvents')->name('user.event');

Route::group(['prefix' => 'events'], function () {
    // 種目作成
    Route::post('', 'EventsController@store')->name('event.store');

    // 種目情報
    Route::get('/{event}', 'EventsController@edit')->name('event.edit');
    
    // 種目更新
    Route::put('/{event}', 'EventsController@update')->name('event.update');
    
    // 種目削除
    Route::delete('/{event}', 'EventsController@destroy')->name('event.destroy');
});


// 種目ログ取得
Route::get('/{log_id}/event_logs', 'EventLogsController@index')->name('event_log.index');

// 作成中のログに登録されている種目ログを全て削除
Route::delete('/{log_id}/event_logs', 'EventLogsController@allDelete')->name('event_log.all_delete');

Route::group(['prefix' => 'event_logs'], function () {
    // 種目ログ作成
    Route::post('', 'EventLogsController@store')->name('event_log.store');

    // 種目ログ削除
    Route::delete('/{event_log_id}', 'EventLogsController@delete')->name('event_log.delete');
});

// コメント作成
Route::post('/comments', 'CommentsController@store')->name('comment.store');

// いいね
Route::post('/favorites', 'FavoritesController@store')->name('favorite.store');

// いいね解除
Route::post('/favorites/{id}', 'FavoritesController@destroy')->name('favorite.destroy');


// トークンリフレッシュ
Route::get('/reflesh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();

    return response()->json();
});