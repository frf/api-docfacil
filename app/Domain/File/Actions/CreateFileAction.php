<?php

namespace Domain\File\Actions;

use Domain\File\Exceptions\UploadFileException;
use Domain\File\Models\File;
use Domain\File\Repositories\FileRepository;
use Illuminate\Http\Request;

class CreateFileAction
{
    /**
     * @var FileRepository
     */
    private FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function execute($uploadData)
    {
        return $this->fileRepository->create($uploadData);
    }

}
