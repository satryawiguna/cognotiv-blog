<?php

namespace App\Services;

use App\Core\Responses\BasicResponse;
use App\Core\Responses\GenericObjectResponse;
use App\Core\Types\HttpResponseType;
use App\Exceptions\InvalidLoginException;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Repositories\Contracts\IUserRepository;
use App\Services\Contracts\IUserService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService implements IUserService
{
    public IUserRepository $_userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->_userRepository = $userRepository;
    }

    public function register(RegisterRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            DB::beginTransaction();
;
            $register = $this->_userRepository->register($request);

            DB::commit();

            $this->setGenericObjectResponse($response,
                $register,
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Register success");

        } catch (QueryException $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                $ex->getMessage());

            Log::error("Invalid query", $response->getMessageResponseError());

        } catch (Exception $ex) {
            DB::rollBack();

            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Invalid register", $response->getMessageResponseError());
        }

        return $response;
    }

    public function login(LoginRequest $request): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $identity = (filter_var($request->identity, FILTER_VALIDATE_EMAIL)) ? 'email' : 'username';

            if (!Auth::attempt([$identity => $request->identity, "password" => $request->password])) {
                throw new InvalidLoginException('Login invalid');
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            $this->setGenericObjectResponse($response,
                [
                    'email' => $user->email,
                    'role' => $user->role->title,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ],
                'SUCCESS',
                HttpResponseType::SUCCESS);

            Log::info("Login succeed");
        } catch (InvalidLoginException $ex) {
            $this->setMessageResponse($response,
                "ERROR",
                HttpResponseType::UNAUTHORIZED,
                $ex->getMessage());

            Log::error("Invalid login", [$response->getMessageResponseErrorLatest()]);
        } catch (\Exception $ex) {
            $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Internal server error", [$response->getMessageResponseError()]);
        }

        return $response;

    }

    public function logout(string $email): BasicResponse
    {
        // TODO: Implement logout() method.
    }

}
