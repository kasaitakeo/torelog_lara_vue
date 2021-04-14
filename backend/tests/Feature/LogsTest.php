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
        $response = $this->get('/api/logs');

        $response
            ->assertStatus(200)
            ->assertJson(["data" => [
                [
                    "id" => $this->log->id,                                 
                    "title" => $this->log->title,                                        
                    "text" => $this->log->text,                                        
                    "created_at" => true,                  
                    "user"=> [
                        "id" => $this->log->user->id,                                            
                        "screen_name" => $this->log->user->screen_name,                   
                        "name" => $this->log->user->name,                             
                        "profile_image" => $this->log->user->profile_image,
                        "email" => $this->log->user->email,                  
                        "profile_text" => $this->log->user->profile_text          
                    ],                                         
                    "favorites" => [],                                      
                    "comments" => [],                                       
                    "event_logs" => []   
                ]
            ]]);
    }

    /**
     * @test
     */
    public function フオローしているユーザーのログを返す()
    {
        $response = $this->actingAs($this->log->user)->get('/api/logs');

        $response
            ->assertStatus(200)
            ->assertJson(["data" => [
                [
                    "id" => $this->log->id,                                 
                    "title" => $this->log->title,                                        
                    "text" => $this->log->text,                                        
                    "created_at" => true,                  
                    "user"=> [
                        "id" => $this->log->user->id,                                            
                        "screen_name" => $this->log->user->screen_name,                   
                        "name" => $this->log->user->name,                             
                        "profile_image" => $this->log->user->profile_image,
                        "email" => $this->log->user->email,                  
                        "profile_text" => $this->log->user->profile_text          
                    ],                                         
                    "favorites" => [],                                      
                    "comments" => [],                                       
                    "event_logs" => []   
                ]
            ]]);
    }

    /**
     * @test
     */
    public function 指定したユーザーのログを返す()
    {
        $response = $this->actingAs($this->log->user)->get('/api/users/3/logs');

        $response
            ->assertStatus(200)
            ->assertJson(["data" => [
                [
                    "id" => $this->log->id,                                 
                    "title" => $this->log->title,                                        
                    "text" => $this->log->text,                                        
                    "created_at" => true,                  
                    "user"=> [
                        "id" => $this->log->user->id,                                            
                        "screen_name" => $this->log->user->screen_name,                   
                        "name" => $this->log->user->name,                             
                        "profile_image" => $this->log->user->profile_image,
                        "email" => $this->log->user->email,                  
                        "profile_text" => $this->log->user->profile_text          
                    ],                                         
                    "favorites" => [],                                      
                    "comments" => [],                                       
                    "event_logs" => []   
                ]
            ]]);
    }

    /**
     * @test
     */
    public function ログの作成()
    {
        $response = $this->actingAs($this->log->user)->json('POST', '/api/logs');

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function ログの更新()
    {
        $response = $this->json('PUT', '/api/logs/6', ['text' => 'test']);

        $response->assertStatus(200);
        
        $this->actingAs($this->log->user)->get('/api/logs/6')
            ->assertJson(['text' => 'test']);
    }

    /**
     * @test
     */
    public function ログの削除()
    {
        $response = $this->json('DELETE', '/api/logs/7');

        $response->assertStatus(200);
    }
}
