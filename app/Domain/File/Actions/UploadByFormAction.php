<?php

namespace Domain\File\Actions;

use Domain\File\Bags\FileBag;
use Domain\File\Models\File;

class UploadByFormAction extends BaseUploadAction
{
    protected string $type = self::TYPE_FORM;

    public function execute(FileBag $fileBag)
    {
        $dataHydrate = $this->processUpload($fileBag);
        return $this->createFileAction->execute($dataHydrate);
    }
}
