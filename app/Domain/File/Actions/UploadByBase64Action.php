<?php

namespace Domain\File\Actions;

use Domain\File\Bags\FileBag;
use Domain\File\Models\File;
use Illuminate\Support\Facades\Storage;

class UploadByBase64Action extends BaseUploadAction
{
    protected string $type = self::TYPE_BASE64;

    public function execute(FileBag $fileBag)
    {
        $dataHydrate = $this->processUpload($fileBag);
        return $this->createFileAction->execute($dataHydrate);
    }
}
