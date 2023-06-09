<?php

namespace App\Services;

use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Core\Types\HttpResponseType;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Repositories\Contracts\IBlogRepository;
use App\Repositories\Contracts\ICommentRepository;
use App\Services\Contracts\ICommentService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CommentService extends BaseService implements ICommentService
{
    private readonly ICommentRepository $_commentRepository;

    private readonly IBlogRepository $_blogRepository;

    public function __construct(ICommentRepository $commentRepository, IBlogRepository $blogRepository)
    {
        $this->_commentRepository = $commentRepository;
        $this->_blogRepository = $blogRepository;
    }

    public function storeComment(int $blogId, CommentStoreRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            DB::beginTransaction();

            $blog = $this->_blogRepository->findById($blogId);

            if ($blog->id != $request->commentable_id) {
                throw new BadRequestException('Path parameter blog id: {' . $blog->id . '} was not match with the request');
            }

            $createComment = $this->_commentRepository->createComment($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $createComment,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Create comment was succeed");
        } catch (QueryException $ex) {
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

    public function updateComment(int $blogId, int $id, CommentUpdateRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        DB::beginTransaction();

        try {
            if ($id != $request->id) {
                throw new BadRequestException('Path parameter id: {' . $id . '} was not match with the request');
            }

            $comment = $this->_commentRepository->findById($id);

            if ($blogId != $comment->commentable_id) {
                throw new BadRequestException('Path parameter blog id: {' . $blogId . '} was not match with the request');
            }

            if (!$comment) {
                throw new ModelNotFoundException('Blog category by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $updateComment = $this->_commentRepository->updateComment($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $updateComment,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Update comment was succeed");
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

    public function destroyComment(int $blogId, int $id): BasicResponse
    {
        $response = new BasicResponse();

        try {
            $comment = $this->_commentRepository->findById($id);

            if ($blogId != $comment->commentable_id) {
                throw new BadRequestException('Path parameter blog id: {' . $blogId . '} was not match with the request');
            }

            if (!$comment) {
                throw new ModelNotFoundException('Blog category by id: {' . $id . '} was not found on ' . __FUNCTION__ . '()');
            }

            $this->_commentRepository->deleteComment($id);

            $this->setMessageResponse($response,
                "SUCCESS",
                HttpResponseType::SUCCESS,
                'Delete comment by id: {' . $id . '} was succeed');

            Log::info('Delete comment by id: {' . $id . '} was succeed');
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

            Log::error("Invalid job destroy", $response->getMessageResponseError());
        }

        return $response;
    }
}
