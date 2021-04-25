<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Log;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /**
     * 指定したカラムのホワイトリスト化（登録/更新を許可するため）
     * @var array
     */
    protected $fillable = [
        'screen_name',
        'name',
        'profile_text',
        'profile_image',
        'email',
        'password'
    ];

    /**
     * responseで返却するデータの指定
     * @var array
     */
    protected $visible = [
        'id', 
        'screen_name', 
        'name', 
        'profile_text',
        'profile_image', 
        'email', 
        'logs', 
        'events'
    ];

     /**
     * モデルの$castsプロパティにカラム名と変換したい型を指定すると、データアクセス時に型変換する
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Eloquentリレーション
     * 多対多の場合
     * フォローされているユーザIDから、フォローしているユーザIDにアクセス
     */
    public function followers()
    {
        // 第三引数：リレーションを定義している（自身の）モデルの外部キー名 第四引数：結合するモデル（相手）の外部キー名
        return $this->belongsToMany(self::class, 'followers', 'followed_id', 'following_id');
    }

    /**
     * Eloquentリレーション
     * 多対多の場合
     * フォローしているユーザーからフォロされているユーザーidへのアクセス
     */
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

    /**
     * フォロー登録
     * @param integer $user_id
     * @return void
     */
    public function follow(Int $user_id) 
    {
        // モデルを結びつけている中間テーブルにレコードを挿入することにより、ユーザーに役割を持たせるにはattachメソッドを使用
        return $this->follows()->attach($user_id);
    }
    
    /**
     * フォロー解除
     * @param integer $user_id
     * @return void
     */
    public function unfollow(Int $user_id)
    {
        // ユーザーから役割を削除する必要がある場合,多対多リレーションのレコードを削除するにはdetachメソッドを使用。detachメソッドは中間テーブルから対応するレコードを削除。しかし両方のモデルはデータベースに残る
        return $this->follows()->detach($user_id);
    }
    
    /**
     * フォローしているか？
     * @param integer $user_id
     * @return boolean
     */
    public function isFollowing(Int $user_id) 
    {
        // Booleanの形でreturnしてフォローしているかの判断
        return (boolean) $this->follows()->where('followed_id', $user_id)->first(['id']);
    }
    
    /**
     * フォローされているか？
     * @param integer $user_id
     * @return boolean
     */
    public function isFollowed(Int $user_id) 
    {
        return (boolean) $this->followers()->where('following_id', $user_id)->first(['id']);
    }
    
    /**
     * ユーザープロフィールの更新処理
     * @param array $data
     * @param array $login_user
     * @return void
     */
    public function updateProfile(Array $data, $login_user)
    {
        // $dataの中に画像があればS3に画像アップロード
        if (isset($data['profile_image'])) {
            // StorageファザードでS3につなぎ、putメソッドを利用してファイルをアップロード
            // 第1引数にはアップロード先のディレクトリ、第2引数にはフォームから受け取ったファイルデータ、第3引数には外部からアクセスできるようにpublicを指定
            $path = Storage::disk('s3')->put('/', $data['profile_image'], 'public');
            
            $this::where('id', $login_user->id)
            ->update([
                'screen_name'   => $data['screen_name'],
                'name'          => $data['name'],
                'profile_text'    => $data['profile_text'],
                'profile_image' => Storage::disk('s3')->url($path),
                'email'         => $data['email'],
            ]);
        } else {
            $this::where('id', $login_user->id)
                ->update([
                    'screen_name'   => $data['screen_name'],
                    'name'          => $data['name'],
                    'profile_text'     => $data['profile_text'],
                    'email'         => $data['email'],
                ]); 
        }
    }
}
