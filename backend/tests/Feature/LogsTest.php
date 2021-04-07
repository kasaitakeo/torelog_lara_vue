<?php

namespace Tests\Feature;

use App\Models\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogsTest extends TestCase
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
    public function 全てのログを返す()
    {
        $response = $this->get('/logs');

        $response
            ->assertStatus(200)
            ->assertJson(['text' => $this->log->text]);
    }
}
