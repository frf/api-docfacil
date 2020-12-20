<?php


namespace App\Core\Services;

use Domain\User\Repositories\UserRepository;

class UserService extends BaseService
{
    /**
     * UserService constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }
}
