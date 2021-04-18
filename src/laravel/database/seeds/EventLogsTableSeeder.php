<?php

use Illuminate\Database\Seeder;
use App\Models\EventLog;

class EventLogsTableSeeder extends Seeder
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
            EventLog::create([
                'log_id' => $i,
                'event_id' => $i,
                'weight' => 30,
                'rep' => 10,
                'set' => 5,
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            EventLog::create([
                'log_id' => $i,
                'event_id' => $i + 20,
                'weight' => 30,
                'rep' => 10,
                'set' => 5,
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            EventLog::create([
                'log_id' => $i,
                'event_id' => $i + 40,
                'weight' => 30,
                'rep' => 10,
                'set' => 5,
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            EventLog::create([
                'log_id' => $i,
                'event_id' => $i + 60,
                'weight' => 30,
                'rep' => 10,
                'set' => 5,
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            EventLog::create([
                'log_id' => $i,
                'event_id' => $i + 80,
                'weight' => 30,
                'rep' => 10,
                'set' => 5,
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            EventLog::create([
                'log_id' => $i,
                'event_id' => $i + 100,
                'weight' => 30,
                'rep' => 10,
                'set' => 5,
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
        for ($i =1; $i <= 20; $i++) {
            EventLog::create([
                'log_id' => $i,
                'event_id' => $i + 120,
                'weight' => 30,
                'rep' => 10,
                'set' => 5,
                'created_at' => now(),
                'updated_at' => now()    
            ]);
        }
    }
}
