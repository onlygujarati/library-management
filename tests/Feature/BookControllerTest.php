<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_book()
    {
        // Sanctum::actingAs(User::factory()->create(['user_type' => 'admin']));

        $response = $this->postJson('/api/create-book', [
            'title' => 'Test Book',
            'author_name' => 'Test Author',
            'status' => 'available'
        ]);

        $response->assertStatus(200);
        // $this->assertDatabaseHas('books', ['title' => 'Test Book']);
    }

    public function test_borrow_book()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $book = Book::factory()->create();

        $response = $this->postJson('/api/books-borrow', [
            'book_id' => $book->id
        ]);

        $response->assertStatus(200);
        // $this->assertDatabaseHas('book_borrows', [
        //     'user_id' => $user->id,
        //     'book_id' => $book->id
        // ]);
    }

    public function test_get_book_list()
    {
        Sanctum::actingAs(User::factory()->create());

        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/book-list');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }
}
