<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_with_valid_data()
    {
        $response = $this->postJson('/api/register', [
            "name" => "test",
            "email" => "test@demo.com",
            "password" => "12345678",
            "user_type" => "user",
            "status" => "active"
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'token']);

        $this->assertDatabaseHas('users', ['email' => 'test@demo.com']);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create([
            'email' => 'test@demo.com',
        ]);
    
        $response = $this->postJson('/api/register', [
            "name" => "test",
            "email" => "test@demo.com",
            "password" => "12345678",
            "user_type" => "user",
            "status" => "active"
        ]);
    
        $response->assertStatus(422);
        $response->assertJsonStructure(['message']); // OR ['errors'] if available
    }

    public function test_user_can_login_with_correct_credentials()
    {
        User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->postJson('/api/login', [
            "email" => "loginuser@example.com",
            "password" => "12345678"
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'token']);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'invaliduser@example.com',
            'password' => bcrypt('correctpassword'),
        ]);
    
        $response = $this->postJson('/api/login', [
            "email" => "invaliduser@example.com",
            "password" => "wrongpassword"
        ]);
    
        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Invalid credentials'
                 ]);
    }
    
}
