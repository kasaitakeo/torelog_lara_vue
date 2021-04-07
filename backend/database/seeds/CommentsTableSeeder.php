<?php

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // ユーザID1のユーザが各ユーザに1つコメント
        for ($i = 1; $i <= 20; $i++) {
            Comment::create([
                'user_id' => 1,
                'log_id' => $i,
                'text' => 'これはテストコメント' .$i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
