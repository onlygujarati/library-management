<?php

use App\Models\Book;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('author_name')->nullable();
            $table->string('status')->comment('available,borrowed')->default('available')->nullable();
            $table->timestamps();
        });

        $book = [
            [
                'title' => 'The Alchemist',
                'author_name' => 'Paulo Coelho',
                'status' => 'available',
            ],
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'author_name' => 'J.K. Rowling',
                'status' => 'available',
            ],
            [
                'title' => 'Pride and Prejudice',
                'author_name' => 'Jane Austen',
                'status' => 'available',
            ]
        ];

        foreach( $book as $data ){
            Book::create($data);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
