<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_any_user_can_get_book_list()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/book-list');

        $response->assertStatus(200);
    }

    public function test_any_user_can_get_book_detail()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/book-detail?id=1'); // use actual ID logic if needed

        $response->assertStatus(200);
    }

    public function test_admin_can_create_book()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/create-book', [
            'title' => 'New Book',
            'author_name' => 'Author Name',
            'status' => 'available'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message']);
    }

    public function test_non_admin_cannot_create_book()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/create-book', [
            'title' => 'New Book',
            'author_name' => 'Author Name',
            'status' => 'available'
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'message' => __('message.permission_denied')
                 ]);
    }

    public function test_admin_can_update_book()
{
    // Create an admin user
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Create a book to update
    $book = Book::factory()->create();

    // Send update request
    $response = $this->actingAs($admin)->postJson('/api/update-book', [
        'id' => $book->id,
        'title' => 'Updated Title',
        'author_name' => 'Updated Author',
        'status' => 'available',
    ]);

    // Assert success response
    $response->assertStatus(200)
             ->assertJsonStructure(['message']);
}

    public function test_non_admin_cannot_delete_book()
    {
        $user = User::factory()->create();
        $user->assignRole('user');
        Sanctum::actingAs($user);

        $book = Book::factory()->create();

        $response = $this->postJson('/api/delete-book', [
            'id' => $book->id
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'message' => __('message.permission_denied')
                 ]);
    }
}
