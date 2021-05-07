<?php

namespace App\Api\Controllers;

use App\Api\Requests\RegisterUserRequest;
use App\Api\Resources\UserResource;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\UpdateUserAction;
use Domain\User\Bags\UserBag;
use Domain\User\Repositories\UserRepository;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserRepository $userRepository;
    private CreateUserAction $createUserAction;
    private UpdateUserAction $updateUserAction;

    public function __construct(
        UserRepository $userRepository,
        CreateUserAction $createUserAction,
        UpdateUserAction $updateUserAction
    ) {
        $this->userRepository = $userRepository;
        $this->createUserAction = $createUserAction;
        $this->updateUserAction = $updateUserAction;
    }

    public function create(RegisterUserRequest $createUserRequest)
    {
        $userBag = UserBag::fromRequest($createUserRequest->validated());
        return UserResource::make($this->createUserAction->execute($userBag));
    }

    public function show($id)
    {
        $user = $this->userRepository->find($id);

        $this->authorize('ownResource', $user);

        if (!$user) {
            throw new ResourceNotFoundException();
        }

        return UserResource::make($user);
    }

    public function update(Request $updateProductRequest, $id)
    {
        if (!$user = $this->userRepository->find($id)) {
            throw new ResourceNotFoundException();
        }

        $this->authorize('ownResource', $user);

        $userBag = UserBag::fromRequest($updateProductRequest, 'update');
        return UserResource::make($this->updateUserAction->execute($userBag, $id));
    }
}
