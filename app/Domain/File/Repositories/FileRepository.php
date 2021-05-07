<?php


namespace Domain\File\Repositories;

use Domain\File\Models\File;
use App\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FileRepository extends Repository
{

    /**
     * @inheritDoc
     */
    public function model()
    {
        return File::class;
    }

    public function all($columns = array('*'))
    {
        $this->applyCriteria();
        return QueryBuilder::for($this->model())
            ->allowedFilters(
                AllowedFilter::partial('name'),
            )
            ->allowedSorts('id', 'name', 'created_at')
            ->paginate();
    }

    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        return QueryBuilder::for($this->model())
            ->find($id, $columns);
    }
}
