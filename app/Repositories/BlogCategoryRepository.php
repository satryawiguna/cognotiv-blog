<?php

namespace App\Repositories;

use App\Core\Entities\BaseEntity;
use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Http\Requests\Blog\BlogCategoryStoreRequest;
use App\Http\Requests\Blog\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use App\Repositories\Contracts\IBlogCategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class BlogCategoryRepository extends BaseRepository implements IBlogCategoryRepository
{
    public function __construct(BlogCategory $blogCategory)
    {
        parent::__construct($blogCategory);
    }

    public function allBlogCategories(ListDataRequest $request): Collection
    {
        $blogCategory = $this->_model;

        return $blogCategory->orderBy($request->order_by, $request->sort)
            ->get();
    }

    public function allSearchBlogCategories(ListSearchDataRequest $request): Collection
    {
        $blogCategory = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $blogCategory = $blogCategory->whereRaw("(title LIKE ?)", $this->searchBlogCategoryByKeyword($keyword));
        }

        return $blogCategory->orderBy($request->order_by, $request->sort)
            ->get();
    }

    public function allSearchPageBlogCategories(ListSearchPageDataRequest $request): LengthAwarePaginator
    {
        $blogCategory = $this->_model;

        if ($request->search) {
            $keyword = $request->search;

            $blogCategory = $blogCategory->whereRaw("(title LIKE ?)", $this->searchBlogCategoryByKeyword($keyword));
        }

        return $blogCategory->orderBy($request->order_by, $request->sort)
            ->paginate($request->per_page, ['*'], 'page', $request->page);
    }

    public function findBlogCategoryById(int $id): BaseEntity|null
    {
        return $this->_model->find($id);
    }

    public function createBlogCategory(BlogCategoryStoreRequest $request): BaseEntity
    {
        $blogCategory = $this->_model->fill($request->all());

        $this->setAuditableInformationFromRequest($blogCategory, $request);

        $blogCategory->save();

        return $blogCategory->fresh();
    }

    public function updateBlogCategory(BlogCategoryUpdateRequest $request): BaseEntity|null
    {
        $blogCategory = $this->_model->find($request->id);

        if (!$blogCategory) {
            return $blogCategory;
        }

        $this->setAuditableInformationFromRequest($blogCategory, $request);

        $blogCategory->fill([
            'title' => $request->title
        ]);

        $blogCategory->save();

        return $blogCategory;
    }

    public function deleteBlogCategory(int $id): BaseEntity|null
    {
        $blogCategory = $this->_model->find($id);

        if (!$blogCategory) {
            return $blogCategory;
        }

        $blogCategory->delete();

        return $blogCategory;
    }

    private function searchBlogCategoryByKeyword(string $keyword) {
        return [
            'title' => "%" . $keyword . "%"
        ];
    }
}
