<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            'id' => $this->id,
            'category' => $this->blogCategory->title,
            'author' => $this->user->contact->nick_name,
            'publish_date' => $this->published_date,
            'status' => $this->status,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content
        ];

        return $resource;
    }
}
