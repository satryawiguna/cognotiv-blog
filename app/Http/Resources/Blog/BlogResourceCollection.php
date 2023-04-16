<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogResourceCollection extends ResourceCollection
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
                'category' => $value->blogCategory->title,
                'author' => $value->user->contact->nick_name,
                'publish_date' => $value->published_date,
                'status' => $value->status,
                'title' => $value->title,
                'slug' => $value->slug,
                'content' => $value->content
            ];
        });

        return $resources->toArray();
    }
}
