<?php


namespace App\Core\Repositories\Criterias;

use App\Core\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class Criteria
{

    /**
     * @param $model
     * @param  RepositoryInterface  $repository
     * @return mixed
     */
    abstract public function apply(Model $model, RepositoryInterface $repository);
}
