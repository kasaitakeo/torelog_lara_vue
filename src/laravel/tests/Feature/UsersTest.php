<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp(): void
    {
        parent::setUp();

        // テストユーザー作成
        $this->user = factory(User::class)->create();

    }

    /**
     * @test
     */
    public function 全てのユーザーデータ取得()
    {
        $response = $this->actingAs($this->user)->get('/api/users');

        $response->assertStatus(200);
    
    }
    /**
     * @test
     */
    public function 指定したidのユーザーデータ取得()
    {
        $response = $this->actingAs($this->user)->get('/api/users/2');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function ユーザーデータの更新()
    {
        $response = $this->actingAs($this->user)
            ->json('PUT', '/api/users', [
                'screen_name'   => 'test',
                'name'          => 'test',
                'profile_text'     => 'test',
                'email'         => 'test@gmail.com',
            ]);

        $response->assertStatus(200);
        
        $this->actingAs($this->user)->get('/api/users/3')
            ->assertJson(['user_data' => ['profiler_text' => 'test']]);
    }

    /**
     * @test
     */
    public function 指定したidのユーザーをフォロー登録とフォロー解除()
    {
        $this->user2 = factory(User::class)->create();

        $response = $this->actingAs($this->user)->post('/api/users/follow/5');      
        
        $response->assertStatus(201);

        $response2 = $this->actingAs($this->user)->post('/api/users/unfollow/5');      
        
        $response2->assertStatus(200);
    }
}
