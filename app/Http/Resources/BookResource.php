<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $auth_user = auth()->user();
        // $book_borrow = $this->bookborrow()->where('user_id', $auth_user->id)->first();
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'author_name'   => $this->author_name,
            'status'        => $this->status,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}