<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookBorrowedMail;

class LogBookBorrowed
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        \Log::info("{$event->user->name} borrowed '{$event->book->title}'");

        try {
            Mail::to($event->user->email)->send(new BookBorrowedMail($event->user, $event->book));
        } catch (\Throwable $th) {
            //throw $th;
            // return false;
        }
    }
}
