<?php

namespace App\Repositories;

use App\Core\Entities\BaseEntity;
use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Http\Requests\Blog\BlogStoreRequest;
use App\Http\Requests\Blog\BlogUpdateRequest;
use App\Models\Blog;
use App\Repositories\Contracts\IBlogRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BlogRepository extends BaseRepository implements IBlogRepository
{
    public function __construct(Blog $blog)
    {
        parent::__construct($blog);
    }

    public function allBlogs(ListDataRequest $request): Collection
    {
        $blog = $this->_model;

        return $blog->orderBy($request->order_by, $request->sort)
            ->get();
    }

    public function allSearchBlogs(ListSearchDataRequest $request): Collection
    {
        $blog = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $blog = $blog->whereRaw("(title LIKE ? OR
                content LIKE ?)", $this->searchBlogByKeyword($keyword));
        }

        if (is_array($request->filter) && count($request->filter) > 0) {
            foreach ($request->filter as $key => $value) {
                switch ($key) {
                    case "published_date":
                        if ($value)
                            $blog = $blog->whereBetween($key, explode("|", $value));
                        break;

                    default:
                        if ($value)
                            $blog = $blog->where($key, $value);
                        break;
                }
            }
        }

        return $blog->orderBy($request->order_by, $request->sort)
            ->get();
    }

    public function allSearchPageBlogs(ListSearchPageDataRequest $request): LengthAwarePaginator
    {
        $blog = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $blog = $blog->whereRaw("(title LIKE ?)", $this->searchBlogByKeyword($keyword));
        }

        if (is_array($request->filter) && count($request->filter) > 0) {
            foreach ($request->filter as $key => $value) {
                switch ($key) {
                    case "published_date":
                        if ($value)
                            $blog = $blog->whereBetween($key, explode("|", $value));
                        break;

                    default:
                        if ($value)
                            $blog = $blog->where($key, $value);
                        break;
                }
            }
        }

        return $blog->orderBy($request->order_by, $request->sort)
            ->paginate($request->per_page, ['*'], 'page', $request->page);
    }

    public function findBlogById(int $id): BaseEntity|null
    {
        return $this->_model->find($id);
    }

    public function createBlog(BlogStoreRequest $request): BaseEntity
    {
        $blog = $this->_model->fill($request->all());

        $this->setAuditableInformationFromRequest($blog, $request);

        $blog->save();

        return $blog->fresh();
    }

    public function updateBlog(BlogUpdateRequest $request): BaseEntity|null
    {
        $blog = $this->_model->find($request->id);

        if (!$blog) {
            return null;
        }

        $this->setAuditableInformationFromRequest($blog, $request);

        $blog->fill([
            'category' => $request->category,
            'author' => $request->author,
            'published_date' => $request->published_date,
            'status' => $request->status,
            'title' => $request->title,
            'content' => $request->content
        ]);

        $blog->save();

        return $blog;
    }

    public function deleteBlog(int $id): BaseEntity|null
    {
        $blog = $this->_model->find($id);

        if (!$blog) {
            return null;
        }

        $blog->delete();

        return $blog;
    }

    private function searchBlogByKeyword(string $keyword) {
        return [
            'title' => "%" . $keyword . "%",
            'content' => "%" . $keyword . "%"
        ];
    }
}
