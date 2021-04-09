<?php

namespace Tests\Feature;

use App\Models\EventLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventLogsTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();

        // テストログ作成
        $this->event_log = factory(EventLog::class)->create();
    }

    /**
     * @test
     */
    public function 指定したlog_idの種目ログを全て取得()
    {
        $response = $this->get('/api/1/event_logs');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function 種目ログを作成()
    {
        $response = $this->json('POST', '/api/event_logs', [
            'log_id' => 2,
            'event_id' => 2,
            'weight' => 50,
            'rep' => 10,
            'set' => 5,
        ]);

        $response->assertStatus(201);

        $response = $this->get('/api/logs/2')
            ->assertJson([
                'event_logs' => [
                    ['weight' => 30],
                    ['weight' => 50]
            ]]);
    }

    /**
     * @test
     */
    public function 指定したidの種目ログを削除する()
    {
        $response = $this->json('DELETE', '/api/event_logs/3');

        $response->assertStatus(200);
    
    }
    /**
     * @test
     */
    public function 指定したlog_idのログに登録されている種目ログを全て削除する()
    {
        $response = $this->json('DELETE', '/api/4/event_logs');

        $response->assertStatus(200);
    }
}
