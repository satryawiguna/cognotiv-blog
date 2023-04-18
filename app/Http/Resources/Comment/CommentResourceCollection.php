<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resources = $this->collection->map(function ($value, $key) {
            return [
                'id' => $value->id,
                'author' => $value->user->contact->nick_name,
                'body' => $value->body,
                'comment_date' => $value->comment_date
            ];
        });

        return $resources->toArray();
    }
}
