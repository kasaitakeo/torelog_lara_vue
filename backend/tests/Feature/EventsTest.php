<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();

        // テストログ作成
        $this->event = factory(Event::class)->create();
    }

    /**
     * @test
     */
    public function 指定したidのユーザーの種目を全て取得()
    {
        $response = $this->get('/api/users/1/events');

        $response->assertStatus(200);
    
    }
    
    /**
     * @test
     */
    public function 種目を作成()
    {
        $response = $this->actingAs($this->event->user)->json('POST', '/api/events', [
            'eventPart' => '背中',
            'eventName' => 'test',
            'eventExplanation' => 'test',
        ]);

        $response->assertStatus(201);

        $response = $this->get('/api/users/2/events')
            ->assertJson([
                [],
                ['event_name' => 'test']
            ]);
    }

    /**
     * @test
     */
    public function 指定したidの種目情報取得()
    {
        $response = $this->get('/api/events/4');

        $response->assertStatus(200);
    
    }
    /**
     * @test
     */
    public function 指定したidの種目を更新()
    {
        $response = $this->actingAs($this->event->user)->json('PUT', '/api/events/5', [
            'eventPart' => '肩',
            'eventName' => 'test',
            'eventExplanation' => 'test',
        ]);

        $response->assertStatus(200);

        $response = $this->get('/api/users/4/events')
            ->assertJson([
                ['event_name' => 'test']
            ]);
    
        }
    /**
     * @test
     */
    public function 指定したidの種目を削除()
    {
        $response = $this->actingAs($this->event->user)->json('DELETE', '/api/events/6');

        $response->assertStatus(200);
    }
}
