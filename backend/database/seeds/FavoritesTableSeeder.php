<?php

use Illuminate\Database\Seeder;
use App\Models\Favorite;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // ユーザID1が全てのログに対して1ついいねを付ける
        for ($i = 1; $i <= 20; $i++) {
            Favorite::create([
                'user_id' => 1,
                'log_id' => $i
            ]);
        }
    }
}
