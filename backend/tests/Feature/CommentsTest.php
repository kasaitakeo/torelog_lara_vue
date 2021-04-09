<?php

namespace Tests\Feature;

use App\Models\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentsTest extends TestCase
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
    public function 指定したコメントの作成()
    {
        $response = $this->actingAs($this->log->user)->json('POST', '/api/comments', [
            'log_id' => 1,
            'text' => 'test',
        ]);

        $response->assertStatus(201);
    }
}
