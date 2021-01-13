<?php

namespace Domain\File\Actions;

use Domain\File\Exceptions\DeleteFileException;
use Domain\File\Models\File;
use Domain\File\Repositories\FileRepository;
use App\Exceptions\ResourceNotFoundException;
use Illuminate\Support\Facades\Http;

class DeleteFileAction
{
    protected string $key;
    protected string $url;
    private FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
//        $this->url = env('COMPANY_CDN_URL');
//        $this->key = env('COMPANY_CDN_KEY');
    }

    public function execute($id)
    {
        $file = $this->fileRepository->find($id);

        if (!$file) {
            throw new ResourceNotFoundException();
        }

        $this->deleteFromCdn($file->hash_name);

        return $this->fileRepository->delete($id);
    }

    public function deleteProfilePictures($user_id)
    {
        $files = $this->fileRepository->findWhere(
            [
                'user_id' => $user_id,
                'type' => File::TYPE_PROFILE_PICTURE
            ]);

        if ($files->count()) {
            foreach ($files as $file) {
                if ($this->deleteFromCdn($file->hash_name)) {
                    $this->fileRepository->delete($file->id);
                }
            }

            return true;
        }

        return false;
    }

    protected function delete($id)
    {
        return Http::withHeaders(['Authorization' => $this->key])->delete($this->url . $id);
    }

    protected function deleteFromCdn($data)
    {
        $delete = $this->delete($data);

        if (!$delete->successful()) {
            throw new DeleteFileException(
                $delete->toPsrResponse()->getReasonPhrase(),
                $delete->toPsrResponse()->getStatusCode()
            );
        }

        return true;
    }
}
