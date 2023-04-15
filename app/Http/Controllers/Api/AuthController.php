<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\User\LoginRequest;
use App\Services\Contracts\IUserService;

class AuthController extends ApiBaseController
{
    private readonly IUserService $_userService;

    /**
     * @param IUserService $userService
     */
    public function __construct(IUserService $userService)
    {
        $this->_userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        $loginResponse = $this->_userService->login($request);

        if ($loginResponse->isError()) {
            return $this->getErrorLatestJsonResponse($loginResponse);
        }

        return $this->getObjectJsonResponse($loginResponse);
    }
}
