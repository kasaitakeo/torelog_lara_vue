<?php

use Illuminate\Database\Seeder;
use App\Models\Log;

class LogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i = 1; $i <= 20; $i++) {
            Log::create([
                'user_id'    => $i,
                'title'       => 'これはテストログtitle' .$i,
                'text'       => 'これはテストログtext' .$i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
