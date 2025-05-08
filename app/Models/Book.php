<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [ 'title', 'author_name', 'status'];


    public function bookborrow(){
        return $this->hasMany(BookBorrow::class, 'book_id', 'id');
    }

    public function scopeMyBookborrowList($query)
    {
        $user = auth()->user();
        $book_borrow = BookBorrow::where('user_id',$user->id)->get()->pluck('book_id');
        // dd($book_borrow);
        $query = $query->whereIn('id',$book_borrow);

        // $query = $query->whereHas('bookborrow', function ($q) use($user) {
        //     $q->where('user_id', $user->id);
        // });

        return $query;
    }
}
