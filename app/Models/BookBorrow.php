<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookBorrow extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'book_id' ];

    protected $casts = [
        'user_id'       => 'integer',
        'book_id'  => 'integer',
    ];

    public function user() {
        return $this->belongsTo( User::class, 'user_id', 'id');
    }

    public function book() {
        return $this->belongsTo( Book::class, 'book_id', 'id');
    }

    public function scopeGetborrowedBook($query)
    {
        $user = auth()->user();

        if ( !$user->hasRole('admin') ) {
            $query = $query->where('user_id',$user->id);
        }

        return $query;
    }
}
