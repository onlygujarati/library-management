<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class BookBorrowedMail extends Mailable
{
    public $user;
    public $book;

    public function __construct($user, $book)
    {
        $this->user = $user;
        $this->book = $book;
    }

    public function build()
    {
        return $this->subject('Book Borrowed Notification')
                    ->view('emails.book_borrowed');
    }
}
