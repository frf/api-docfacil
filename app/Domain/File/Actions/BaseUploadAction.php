<?php

namespace Domain\File\Actions;

use Domain\File\Bags\FileBag;
use Domain\File\Exceptions\UploadFileException;
use Faker\Provider\Uuid;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class BaseUploadAction
{
    protected string $key;
    protected string $url;
    protected string $type = '';

    public const TYPE_URL = 'url';
    public const TYPE_FORM = 'form';
    public const TYPE_BASE64 = 'base64';

    protected CreateFileAction $createFileAction;
    protected DeleteFileAction $deleteFileAction;

    public function __construct(CreateFileAction $createFileAction, DeleteFileAction $deleteFileAction)
    {
        $this->createFileAction = $createFileAction;
        $this->deleteFileAction = $deleteFileAction;
    }

    protected function uploadToCdn($data)
    {
        $upload = $this->upload($data);

        if (!$upload) {
            throw new UploadFileException();
        }

        return $upload;
    }

    protected function upload($data)
    {
        $data['name'] = $this->createName($data['name']);

        if ($this->type === self::TYPE_FORM) {
            return $this->uploadMultiPart($data);
        }

        if ($this->type === self::TYPE_BASE64) {
            return $this->uploadBase64($data);
        }

        if ($this->type === self::TYPE_URL) {
            return $this->uploadUrl($data);
        }

        return $this->uploadGeneral($data);
    }

    protected function uploadGeneral($data)
    {
            return $this->uploadS3($data);
    }

    protected function uploadS3($data)
    {
        $s3 = Storage::disk('s3')
            ->put($data['hash_name'], $data['file'], 'public');

        if ($s3) {
            $size = Storage::disk('s3')
                ->size($data['hash_name']);
            $url = Storage::disk('s3')
                ->url($data['hash_name']);

            $data['url'] = $url;
            $data['path'] = $url;
            $data['size'] = $size;
            $data['bucket'] = env('AWS_BUCKET');
            unset($data['file']);

            return $data;
        }

        return false;
    }

    protected function uploadMultiPart($data)
    {
        /** @var File $file */
        $file = $data['file'];
        unset($data['file']);

        $contents = $file->get();
        $data['hash_name'] = $file->hashName();
        $data['content_type'] = $file->getMimeType();
        $data['file'] = base64_encode($contents);

        return $this->uploadGeneral($data);
    }

    protected function uploadBase64($data)
    {
        $imageType = preg_match('/^data:(image\/(\w+));base64,/', $data['file'], $matches);
        $data['content_type'] = ($imageType) ? $matches[1] : 'image/png';
        $contentTypeData = explode('/', $data['content_type']);
        $extension = (isset($contentTypeData[1])) ? $contentTypeData[1] : '.png';
        $data['name'] = $data['name'] . '.' . $extension;
        $data['hash_name'] = Uuid::uuid() . '_' . $data['name'];
        $image_parts = explode(";base64,", $data['file']);

        if (!isset($image_parts[1])) {
            throw new UploadFileException();
        }

        $data['file'] = base64_decode($image_parts[1]);

        return $this->uploadGeneral($data);
    }

    protected function uploadUrl($data)
    {
        $pathFile = parse_url($data['url'], PHP_URL_PATH);
        $imageName = preg_match('~([^/]+)\.(\w+)~', $pathFile, $matches);
        $data['content_type'] = ($imageName) ? $matches[2] : 'image/png';
        $data['hash_name'] = Uuid::uuid() . '_' .  ($imageName) ?? str_replace(' ', '', $matches[0]);
        $data['path'] = $data['url'];
        $data['bucket'] = env('AWS_BUCKET');

        return $data;
    }

    protected function hydrate($uploadData)
    {
        return [
            'application' => 'api-docfacil',
            'name' => $uploadData['name'],
            'hash_name' => $uploadData['hash_name'],
            'path' => $uploadData['path'],
            'content_type' => $uploadData['content_type'],
            'driver' => 's3',
            'bucket' => $uploadData['bucket'],
            'url' => $uploadData['url'],
            'metadata' => json_encode($uploadData['metadata']),
        ];
    }

    protected function processUpload(FileBag $fileBag) : array
    {
        $data = $fileBag->attributes();

        if (!$data['user_id'] && auth()->user()->hasRole('sa')) {
            $data['user_id'] = auth()->user()->getAuthIdentifier();
        }

        $dataToUpload = [
            'file' => $data['file'],
            'name' => $data['name'],
            'metadata' => $data['metadata'],
        ];

        $uploadData = $this->uploadToCdn($dataToUpload);
        $dataHydrate = $this->hydrate($uploadData);

        $dataHydrate['type'] = $data['type'];
        $dataHydrate['user_id'] = $data['user_id'];

        if ($data['type'] == \Domain\File\Models\File::TYPE_PROFILE_PICTURE) {
            //$this->deleteFileAction->deleteProfilePictures($data['user_id']);
        }

        return $dataHydrate;
    }

    protected function createName($name): string
    {
        return ($name) ?
            preg_replace('/[^A-Za-z0-9\.]/i', ' ', $name) :
            'doc-facil';
    }
}
