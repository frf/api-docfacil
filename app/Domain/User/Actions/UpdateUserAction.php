<?php

namespace Domain\User\Actions;

use Domain\User\Bags\UserBag;
use Domain\User\Repositories\UserRepository;

class UpdateUserAction
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UserBag $userBag, int $id)
    {
        $dataToUpdate = $userBag->attributes();

        $this->userRepository->update($dataToUpdate, $id);

        return $this->userRepository->find($id);
    }
}
