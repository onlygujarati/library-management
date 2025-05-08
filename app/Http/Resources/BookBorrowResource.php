<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookBorrowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,
            'user_name'     => optional($this->user)->name,
            'book_id'       => $this->book_id,
            'book_name'     => optional($this->book)->title,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}