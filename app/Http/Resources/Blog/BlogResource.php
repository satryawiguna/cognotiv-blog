<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Comment\CommentResourceCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'category_title' => $this->blogCategory->title,
            'category' =>  $this->blogCategory->id,
            'author_name' => $this->user->contact->nick_name,
            'author' => $this->user->id,
            'published_date' => Carbon::parse($this->published_date)->format("Y-m-d"),
            'status' => $this->status,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content
        ];

        if (is_array($request->relations) && count($request->relations) > 0) {
            if (in_array('comments', $request->relations)) {
                $blog['comments'] = new CommentResourceCollection($this->comments);
            }
        } else {
            $relations = explode(',', $request->relations);

            if (in_array('comments', $relations)) {
                $blog['comments'] = new CommentResourceCollection($this->comments);
            }
        }

        return $blog;
    }
}
