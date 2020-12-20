<?php

namespace Domain\File\Actions;

use Domain\File\Bags\FileBag;
use Domain\File\Models\File;

class UploadByUrlAction extends BaseUploadAction
{
    protected string $type = self::TYPE_URL;

    public function execute(FileBag $fileBag)
    {
        $data = $fileBag->attributes();

        if (!$data['user_id'] && auth()->user()->hasRole('sa')) {
            $data['user_id'] = auth()->user()->getAuthIdentifier();
        }

        $dataToUpload = [
            'url' => $data['url'],
            'name' => $data['name'],
            'metadata' => $data['metadata'],
        ];

        $uploadData = $this->uploadToCdn($dataToUpload);
        $dataHydrate = $this->hydrate($uploadData);

        $dataHydrate['type'] = $data['type'];
        $dataHydrate['user_id'] = $data['user_id'];

        if ($data['type'] == File::TYPE_PROFILE_PICTURE) {
//            $this->deleteFileAction->deleteProfilePictures($data['user_id']);
        }

        return $this->createFileAction->execute($dataHydrate);
    }
}
