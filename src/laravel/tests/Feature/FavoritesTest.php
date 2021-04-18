<?php

namespace Tests\Feature;

use App\Models\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // テストログ作成
        $this->log = factory(Log::class)->create();
    }

    /**
     * @test
     */
    public function 指定したlog_idのログにいいねし、その後いいね解除()
    {
        $response = $this->actingAs($this->log->user)->json('POST', '/api/favorites', [
            'log_id' => 1,
        ]);

        $response->assertStatus(201);

        $response = $this->actingAs($this->log->user)->json('POST', '/api/favorites/1');

        $response->assertStatus(200);
    }
}
