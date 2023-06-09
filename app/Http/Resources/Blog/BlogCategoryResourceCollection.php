<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCategoryResourceCollection extends ResourceCollection
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
                'title' => $value->title,
                'slug' => $value->slug
            ];
        });

        return $resources->toArray();
    }
}
