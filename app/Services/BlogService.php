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
use App\Http\Requests\Blog\BlogStoreRequest;
use App\Http\Requests\Blog\BlogUpdateRequest;
use App\Repositories\Contracts\IBlogRepository;
use App\Repositories\Contracts\ILikeRepository;
use App\Services\Contracts\IBlogService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class BlogService extends BaseService implements IBlogService
{
    private readonly IBlogRepository $_blogRepository;

    private readonly ILikeRepository $_likeRepository;

    public function __construct(IBlogRepository $blogRepository, ILikeRepository $likeRepository)
    {
        $this->_blogRepository = $blogRepository;
        $this->_likeRepository = $likeRepository;
    }

    public function getAllBlogs(ListDataRequest $request): GenericListResponse
    {
        $response = new GenericListResponse();

        try {
            $bolgs = $this->_blogRepository->allBlogs($request);

            $this->setGenericListResponse($response,
                $bolgs,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all blog was succeed");
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

    public function getAllSearchBlogs(ListSearchDataRequest $request): GenericListSearchResponse
    {
        $response = new GenericListSearchResponse();

        try {
            $bolgs = $this->_blogRepository->allSearchBlogs($request);

            $this->setGenericListSearchResponse($response,
                $bolgs,
                $bolgs->count(),
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search blog was succeed");
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

    public function getAllSearchPageBlogs(ListSearchPageDataRequest $request): GenericListSearchPageResponse
    {
        $response = new GenericListSearchPageResponse();

        try {
            $blogs = $this->_blogRepository->allSearchPageBlogs($request);

            $this->setGenericListSearchPageResponse($response,
                $blogs->getCollection(),
                $blogs->total(),
                ["per_page" => $blogs->perPage(), "current_page" => $blogs->currentPage()],
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch all by search page blog was succeed");
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

    public function getBlog(int $id): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $bolg = $this->_blogRepository->findBlogById($id);

            if (!$bolg) {
                throw new ModelNotFoundException("Blog by id: {' .  $id . '} was not found on " . __FUNCTION__ . "()");
            }

            $this->setGenericObjectResponse($response,
                $bolg,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Fetch blog was succeed");
        } catch (QueryException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (ModelNotFoundException $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid object not found');

            Log::error('Invalid object not found on ' . __FUNCTION__ . '()', [$ex->getMessage()]);
        } catch (Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function storeBlog(BlogStoreRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            DB::beginTransaction();

            $createBlog = $this->_blogRepository->createBlog($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $createBlog,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Create blog was succeed");
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

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                'Something went wrong');

            Log::error("Something went wrong on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        }

        return $response;
    }

    public function updateBlog(int $id, BlogUpdateRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        DB::beginTransaction();

        try {
            if ($id != $request->id) {
                throw new BadRequestException('Path parameter id: {' . $id . '} was not match with the request');
            }

            $blog = $this->_blogRepository->findById($id);

            if (!$blog) {
                throw new ModelNotFoundException('Blog by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $updateBlog = $this->_blogRepository->updateBlog($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $updateBlog,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Update blog was succeed");
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

    public function destroyBlog(int $id): BasicResponse
    {
        $response = new BasicResponse();

        try {
            $blog = $this->_blogRepository->findById($id);

            if (!$blog) {
                throw new ModelNotFoundException('Blog by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $this->_blogRepository->deleteBlog($id);

            $this->setMessageResponse($response,
                "SUCCESS",
                HttpResponseType::SUCCESS,
                'Delete blog by id: {' . $id . '} was succeed');

            Log::info('Delete blog by id: {' . $id . '} was succeed');
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
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Invalid blog destroy", $response->getMessageResponseError());
        }

        return $response;
    }

    public function likeAndDislikeBlog(int $blogId): BasicResponse
    {
        $response = new BasicResponse();

        try {
            $like = $this->_likeRepository->findLikeByBlogIdAndUserId($blogId, Auth::user()->id);

            if (!$like) {
                $this->_blogRepository->likeBlog($blogId, Auth::user()->id);

                $this->setMessageResponse($response,
                    "SUCCESS",
                    HttpResponseType::SUCCESS,
                    'Like blog by id: {' . $blogId . '} was succeed');

                Log::info('Like blog by id: {' . $blogId . '} was succeed');
            } else {
                $this->_blogRepository->dislikeBlog($blogId, Auth::user()->id);

                $this->setMessageResponse($response,
                    "SUCCESS",
                    HttpResponseType::SUCCESS,
                    'Dislike blog by id: {' . $blogId . '} was succeed');

                Log::info('Dislike blog by id: {' . $blogId . '} was succeed');
            }
        } catch(QueryException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                'Invalid query');

            Log::error("Invalid query on " . __FUNCTION__ . "()", [$ex->getMessage()]);
        } catch (\Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Invalid blog destroy", $response->getMessageResponseError());
        }

        return $response;
    }
}
