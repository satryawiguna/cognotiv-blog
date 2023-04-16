<?php

namespace App\Services;

use App\Core\Requests\ListDataRequest;
use App\Core\Requests\ListSearchDataRequest;
use App\Core\Requests\ListSearchPageDataRequest;
use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericListResponse;
use App\Core\Responses\GenericListSearchPageResponse;
use App\Core\Responses\GenericListSearchResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Core\Types\HttpResponseType;
use App\Http\Requests\Blog\BlogCategoryStoreRequest;
use App\Http\Requests\Blog\BlogCategoryUpdateRequest;
use App\Repositories\Contracts\IBlogCategoryRepository;
use App\Services\Contracts\IBlogService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class BlogService extends BaseService implements IBlogService
{
    public IBlogCategoryRepository $_blogCategoryRepository;

    public function __construct(IBlogCategoryRepository $blogCategoryRepository)
    {
        $this->_blogCategoryRepository = $blogCategoryRepository;
    }

    public function getAllBlogCategories(ListDataRequest $request): GenericListResponse
    {
        $response = new GenericListResponse();

        try {
            $bolgCategories = $this->_blogCategoryRepository->allBlogCategories($request);

            $this->setGenericListResponse($response,
                $bolgCategories,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all blog category was succeed");
        } catch (QueryException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function getAllSearchBlogCategories(ListSearchDataRequest $request): GenericListSearchResponse
    {
        $response = new GenericListSearchResponse();

        try {
            $bolgCategories = $this->_blogCategoryRepository->allSearchBlogCategories($request);

            $this->setGenericListSearchResponse($response,
                $bolgCategories,
                $bolgCategories->count(),
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search blog category was succeed");
        } catch (QueryException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function getAllSearchPageBlogCategories(ListSearchPageDataRequest $request): GenericListSearchPageResponse
    {
        $response = new GenericListSearchPageResponse();

        try {
            $blogCategories = $this->_blogCategoryRepository->allSearchPageBlogCategories($request);

            $this->setGenericListSearchPageResponse($response,
                $blogCategories->getCollection(),
                $blogCategories->count(),
                ["perPage" => $blogCategories->perPage(), "currentPage" => $blogCategories->currentPage()],
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search page blog category was succeed");
        } catch (QueryException $ex) {
            dd($ex->getMessage());
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function getBlogCategory(int $id): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $bolgCategory = $this->_blogCategoryRepository->findBlogCategoryById($id);

            if (!$bolgCategory) {
                throw new ModelNotFoundException("Blog category by id: {$id} was not found on " . __FUNCTION__ . "()");
            }

            $this->setGenericObjectResponse($response,
                $bolgCategory,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch blog category was succeed");
        } catch (QueryException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (ModelNotFoundException $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid object not found');

            Log::error('Invalid object not found on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch (Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function storeBlogCategory(BlogCategoryStoreRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            DB::beginTransaction();

            $createBlogCategory = $this->_blogCategoryRepository->createBlogCategory($request);

            DB::commit();

            $response = $this->setGenericObjectResponse($response,
                $createBlogCategory,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Create blog category was succeed");
        } catch (QueryException $ex) {
            dd($ex->getMessage());
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (BadRequestException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Bad request');

            Log::error("Bad request on " . __FUNCTION__ . "()", [$ex->getMessage()]);

        } catch (Exception $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function updateBlogCategory(int $id, BlogCategoryUpdateRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        DB::beginTransaction();

        try {
            if ($id != $request->id) {
                throw new BadRequestException('Path parameter id: {' . $id . '} was not match with the request');
            }

            $blogCategory = $this->_blogCategoryRepository->findById($id);

            if (!$blogCategory) {
                throw new ModelNotFoundException('Blog category by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $updateBlogCategory = $this->_blogCategoryRepository->updateBlogCategory($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $updateBlogCategory,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Update blog category was succeed");
        } catch(QueryException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (ModelNotFoundException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND,
                'Invalid object not found');

            Log::error('Invalid object not found on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch (BadRequestException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND,
                'Bad request');

            Log::error('Bad request on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch (Exception $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function destroyBlogCategory(string $id): BasicResponse
    {
        $response = new BasicResponse();

        try {
            $blogCategory = $this->_blogCategoryRepository->findById($id);

            if (!$blogCategory) {
                throw new ModelNotFoundException('Blog category by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $this->_blogCategoryRepository->deleteBlogCategory($id);

            $this->setMessageResponse($response,
                "SUCCESS",
                HttpResponseType::SUCCESS,
                'Delete bog category by id: {' . $id . '} was succeed');

            Log::info('Delete bog category by id: {' . $id . '} was succeed');
        } catch (ModelNotFoundException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND,
                'Invalid object not found');

            Log::error('Invalid object not found on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch(QueryException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (BadRequestException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND,
                'Bad request');

            Log::error('Bad request on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch (\Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Invalid job destroy", $response->getMessageResponseError());
        }

        return $response;
    }

}
