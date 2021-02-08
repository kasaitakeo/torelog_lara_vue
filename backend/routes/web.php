<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// APIのURL以外のリクエストに対してはindexテンプレートを返す
// 画面遷移はフロントエンドのVueRouterが制御する
// {any?} で任意のパスパラメータ any を受け入れています。次に where メソッドで正規表現を用いて any パスパラメータの文字列形式を定義していますが、.+ で「任意の文字が一文字以上」つまり「なんでもいい」という指定をしています。まとめると、any パラメータはあってもなくてもいい（?）し、ある場合はどんな文字列でもいい（.+）ということになります。
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
