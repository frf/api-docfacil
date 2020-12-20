<?php

namespace Domain\User\Actions;

use Domain\User\Bags\UserBag;
use Domain\User\Models\User;
use Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateUserAction
{
    protected UserRepository $userRepository;

    /**
     * RegisterUserAction constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function execute(UserBag $data): User
    {
        $data = $data->attributes();
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);
        $user->guard(['api'])->assignRole(['user']);
        $user->save();

        return $user;
    }
}
