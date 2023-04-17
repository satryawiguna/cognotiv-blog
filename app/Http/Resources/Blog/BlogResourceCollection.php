<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Comment\CommentResourceCollection;
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
        $resources = $this->collection->map(function ($value, $key) use ($request) {
            $blog = [
                'id' => $value->id,
                'category_id' => ($value->blogCategory) ? $value->blogCategory->id : null,
                'category' => ($value->blogCategory) ? $value->blogCategory->title : null,
                'author_id' => ($value->user) ? $value->user->id : null,
                'author' => ($value->user && $value->user->contact) ? $value->user->contact->nick_name : null,
                'publish_date' => $value->published_date,
                'status' => $value->status,
                'title' => $value->title,
                'slug' => $value->slug,
                'content' => $value->content
            ];

            if (is_array($request->relations) && count($request->relations) > 0) {
                if (in_array('comments', $request->relations)) {
                    $blog['comments'] = new CommentResourceCollection($value->comments);
                }
            }

            return $blog;
        });

        return $resources->toArray();
    }
}
