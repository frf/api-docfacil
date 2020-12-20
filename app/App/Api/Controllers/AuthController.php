<?php

namespace App\Api\Controllers;

use App\Api\Requests\LoginUserRequest;
use App\Api\Requests\RegisterUserRequest;
use App\Api\Resources\UserResource;
use Domain\User\Actions\AuthUserAction;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Bags\UserBag;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected CreateUserAction $registerUserAction;
    protected AuthUserAction $authUserAction;

    /**
     * AuthController constructor.
     * @param CreateUserAction $registerUserAction
     * @param AuthUserAction $authUserAction
     */
    public function __construct(CreateUserAction $registerUserAction,
                                AuthUserAction $authUserAction)
    {
        $this->registerUserAction = $registerUserAction;
        $this->authUserAction = $authUserAction;
    }

    public function register(RegisterUserRequest $registerUserRequest)
    {
        $userBag = UserBag::fromRequest($registerUserRequest->validated());
        return $this->registerUserAction->execute($userBag);
    }

    public function login(LoginUserRequest $loginUserRequest)
    {
        $userBag = UserBag::fromRequest($loginUserRequest->validated());
        return $this->authUserAction->execute($userBag);
    }

    public function me()
    {
        return UserResource::make(auth()->user());
    }
}
