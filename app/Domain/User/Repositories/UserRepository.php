<?php


namespace Domain\User\Repositories;

use Domain\User\Models\User;
use App\Core\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends Repository
{
    public function model()
    {
        return User::class;
    }

    public function all($columns = array('*'))
    {
        $this->applyCriteria();
        return QueryBuilder::for($this->model)
            ->allowedFilters(
                AllowedFilter::partial('name'),
                AllowedFilter::partial('email'),
                )
            ->allowedSorts('id', 'name', 'created_at')
            ->paginate();
    }

    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        return QueryBuilder::for($this->model)
            ->find($id, $columns);
    }

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id, $attribute = "id")
    {
        return parent::update($data, $id, $attribute);
    }
}
