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

    public function deleteProfilePictures($id)
    {
        return $this->deleteWhere([
            'user_id' => $id,
            'type' => File::TYPE_PROFILE_PICTURE
        ]);
    }
}
