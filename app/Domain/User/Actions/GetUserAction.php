<?php

namespace Domain\User\Actions;

use App\Actions\ActionInterface;
use Domain\User\Dto\CreateUserDto;
use Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;

class GetUserAction
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($id)
    {
        return $this->userRepository->show($id);
    }
}
