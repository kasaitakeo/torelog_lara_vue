<?php

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i =1; $i <= 20; $i++) {
            Event::create([
                'user_id' => $i,
                'event_part' => '胸',
                'event_name' => 'ベンチプレス',
                'event_explanation' => '大胸筋狙い',
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            Event::create([
                'user_id' => $i,
                'event_part' => '背中',
                'event_name' => 'ラットプルダウン',
                'event_explanation' => '広背筋上部狙い',
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            Event::create([
                'user_id' => $i,
                'event_part' => '肩',
                'event_name' => 'ショルダープレス',
                'event_explanation' => '三角筋前部狙い',
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            Event::create([
                'user_id' => $i,
                'event_part' => '脚',
                'event_name' => 'スクワット',
                'event_explanation' => '大腿四頭筋、大臀筋狙い',
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            Event::create([
                'user_id' => $i,
                'event_part' => '上腕二頭筋',
                'event_name' => 'アームカール',
                'event_explanation' => '上腕二頭筋短頭狙い',
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            Event::create([
                'user_id' => $i,
                'event_part' => '上腕三頭筋',
                'event_name' => 'スカルクラッシャー',
                'event_explanation' => '上腕三頭筋狙い',
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            Event::create([
                'user_id' => $i,
                'event_part' => '腹筋',
                'event_name' => 'ケーブルアブドミナルクランチ',
                'event_explanation' => '腹直筋狙い',
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
    }
}
