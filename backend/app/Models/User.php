<?php

// Userは元々app直下にあるのでapp/Modelsに移動してください。その際namespaceを変更するのを忘れずに
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Log;
use App\Models\Event;

class User extends Authenticatable
{
    // 通知先にしたいクラスで Notifiable trait を use するだけ
    // 通知先は以下の機能を持ちます。通知送信メソッド、通知に伴う通信先情報の提供
    use Notifiable;

    // screen_nameとprofile_imageを追加したので、登録/更新を許可するために$fillable(表示するカラムの選択)の配列にカラムを指定します。
    protected $fillable = [
        // 'name', 'email', 'password',
        'screen_name',
        'name',
        'profile_image',
        'email',
        'password'
    ];

    // ユーザーは複数人のユーザをフォローするため多対多のリレーションになる。→中間テーブルとしてfollowersテーブルにユーザ間の関係をまとめる

    // 第二引数： 結合テーブル名 第三引数： リレーションを定義している（自身の）モデルの外部キー名 第四引数： 結合するモデル（相手）の外部キー名

    // 第一引数で参照するテーブルを指定するが、今回は同一テーブルなので自身のテーブルになる。第二引数には中間テーブルとなるfolloersテーブルを指定。

    // followers()はフォローされているユーザIDから、フォローしているユーザIDにアクセスする。follows()はその逆向きのアクセス。

    public function followers()
    {
        return $this->belongsToMany(self::class, 'followers', 'followed_id', 'following_id');
    }

    public function follows()
    {
        return $this->belongsToMany(self::class, 'followers', 'following_id', 'followed_id');
    }

    /**
     * Eloquentリレーション
     * １対多の場合(メソッド名が複数形)
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    
    }
    /**
     * Eloquentリレーション
     * １対多の場合(メソッド名が複数形)
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    
    // フォローする
    public function follow(Int $user_id) 
    {
        // モデルを結びつけている中間テーブルにレコードを挿入することにより、ユーザーに役割を持たせるにはattachメソッドを使用
        return $this->follows()->attach($user_id);
    }
    
    // フォロー解除する
    public function unfollow(Int $user_id)
    {
        // ユーザーから役割を削除する必要がある場合,多対多リレーションのレコードを削除するにはdetachメソッドを使用。detachメソッドは中間テーブルから対応するレコードを削除。しかし両方のモデルはデータベースに残る
        return $this->follows()->detach($user_id);
    }
    
    // フォローしているか
    public function isFollowing(Int $user_id) 
    {
        // Booleanの形でreturnしてフォローしているかの判断
        return (boolean) $this->follows()->where('followed_id', $user_id)->first(['id']);
        //  return $this->follows()->where('followed_id', $user_id)->first(['id']);
    }
    
    // フォローされているか
    public function isFollowed(Int $user_id) 
    {
        return (boolean) $this->followers()->where('following_id', $user_id)->first(['id']);
    }
    
    // 引数で受け取ったログインしているユーザを除くユーザを1ページにつき5名取得
    public function getAllUsers(Int $user_id)
    {
        // クエリビルダのwhere()で該当するデータだけ取得
        return $this->Where('id', '<>', $user_id)->paginate(5);
    }
    
    //  プロフィール編集
    public function updateProfile(Array $data, $login_user)
    {
        // $dataの中に画像があれば処理を分けています
        if (isset($data['profile_image'])) {
            // $file_name = $data['profile_image']->store('public/profile_image/');こうすることで画像ファイルが/storage/app/public/profile_image/に保存されます。
            // $file_name = $data['profile_image']->store('public/profile_image/');
            $file_name = time() . '.' . $data['profile_image']->getClientOriginalName();
            $data['profile_image']->storeAs('public', $file_name);
            
            // $this::where('id', $this->id)
            $this::where('id', $login_user->id)
            ->update([
                'screen_name'   => $data['screen_name'],
                'name'          => $data['name'],
                'user_text'     => $data['user_text'],
                'profile_image' => '/storage/' . basename($file_name),
                'email'         => $data['email'],
                ]);
        } else {
            $this::where('id', $login_user->id)
                ->update([
                    'screen_name'   => $data['screen_name'],
                    'name'          => $data['name'],
                    'user_text'     => $data['user_text'],
                    'email'         => $data['email'],
                ]); 
        }

        return;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


}
