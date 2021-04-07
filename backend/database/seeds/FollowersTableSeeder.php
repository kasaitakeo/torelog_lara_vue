<?php

use Illuminate\Database\Seeder;
use App\Models\Follower;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザID1を各ユーザがフォロー
        for ($i = 2; $i <= 20; $i++) {
            Follower::create([
                'following_id' => $i,
                'followed_id' => 1
            ]);
        }
    }
}
