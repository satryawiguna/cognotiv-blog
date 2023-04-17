<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Comment\CommentResourceCollection;
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
        $blog = [
            'id' => $this->id,
            'category' => $this->blogCategory->title,
            'author' => $this->user->contact->nick_name,
            'publish_date' => $this->published_date,
            'status' => $this->status,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content
        ];

        if (is_array($request->relations) && count($request->relations) > 0) {
            if (in_array('comments', $request->relations)) {
                $blog['comments'] = new CommentResourceCollection($this->comments);
            }
        }

        return $blog;
    }
}
