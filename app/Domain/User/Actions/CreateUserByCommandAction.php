<?php


namespace Domain\User\Actions;

use Domain\User\Bags\UserBag;

class CreateUserByCommandAction
{
    protected $createUserAction;

    public function __construct(CreateUserAction $createUserAction)
    {
        $this->createUserAction = $createUserAction;
    }

    public function execute(UserBag $userBag)
    {
        return $this->createUserAction->execute($userBag);
    }
}
