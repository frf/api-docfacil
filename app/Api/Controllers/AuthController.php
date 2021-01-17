<?php

namespace App\Api\Controllers;

use App\Api\Requests\LoginUserRequest;
use App\Api\Requests\RegisterUserRequest;
use App\Api\Resources\UserResource;
use Domain\User\Actions\AuthUserAction;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\SendMailAction;
use Domain\User\Bags\UserBag;
use Domain\User\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected CreateUserAction $createUserAction;
    protected AuthUserAction $authUserAction;
    protected SendMailAction $sendMailAction;

    public function __construct(
        CreateUserAction $createUserAction,
        AuthUserAction $authUserAction,
        SendMailAction $sendMailAction
    ) {
        $this->createUserAction = $createUserAction;
        $this->authUserAction = $authUserAction;
        $this->sendMailAction = $sendMailAction;
    }

    public function register(RegisterUserRequest $registerUserRequest)
    {
        $userBag = UserBag::fromRequest($registerUserRequest->validated());
        $user = $this->createUserAction->execute($userBag);

        if ($user instanceof User) {
            $this->sendMailAction->execute($user);
        }

        return $this->authUserAction->execute($userBag);
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
