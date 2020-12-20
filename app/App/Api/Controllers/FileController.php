<?php

namespace App\Api\Controllers;

use App\Api\Requests\CreateUploadFileBase64Request;
use App\Api\Requests\CreateUploadFileFormRequest;
use App\Api\Requests\CreateUploadFileUrlRequest;
use Domain\File\Actions\DeleteFileAction;
use Domain\File\Actions\UploadByBase64Action;
use Domain\File\Actions\UploadByFormAction;
use Domain\File\Actions\UploadByUrlAction;
use Domain\File\Bags\FileBag;
use Illuminate\Http\Resources\Json\JsonResource;

class FileController extends Controller
{
    /**
     * @var UploadByBase64Action
     */
    private UploadByBase64Action $uploadByBase64Action;
    /**
     * @var UploadByUrlAction
     */
    private UploadByUrlAction $uploadByUrlAction;
    /**
     * @var UploadByFormAction
     */
    private UploadByFormAction $uploadByFormAction;
    /**
     * @var DeleteFileAction
     */
    private DeleteFileAction $deleteFileAction;

    public function __construct(
        UploadByBase64Action $uploadByBase64Action,
        UploadByUrlAction $uploadByUrlAction,
        UploadByFormAction $uploadByFormAction,
        DeleteFileAction $deleteFileAction
    ) {
        $this->uploadByBase64Action = $uploadByBase64Action;
        $this->uploadByUrlAction = $uploadByUrlAction;
        $this->uploadByFormAction = $uploadByFormAction;
        $this->deleteFileAction = $deleteFileAction;
    }

    public function uploadBase64(CreateUploadFileBase64Request $request)
    {
        $data = FileBag::fromRequest($request->validated());
        $fileUpload = $this->uploadByBase64Action->execute($data);
        return JsonResource::make($fileUpload);
    }

    public function uploadUrl(CreateUploadFileUrlRequest $request)
    {
        $data = FileBag::fromRequest($request->validated());
        $fileUpload = $this->uploadByUrlAction->execute($data);
        return JsonResource::make($fileUpload);
    }

    public function uploadForm(CreateUploadFileFormRequest $request)
    {
        $data = FileBag::fromRequest($request->validated());
        $fileUpload = $this->uploadByFormAction->execute($data);
        return JsonResource::make($fileUpload);
    }

    public function destroy($id)
    {
        return response()->json(
            json_encode($this->deleteFileAction->execute($id))
        );
    }
}
