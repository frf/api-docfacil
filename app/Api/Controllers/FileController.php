<?php

namespace App\Api\Controllers;

use App\Api\Requests\CreateUploadFileBase64Request;
use App\Api\Requests\CreateUploadFileFormRequest;
use App\Api\Requests\CreateUploadFileUrlRequest;
use App\Api\Resources\FileResource;
use App\Api\Resources\ProductResource;
use Domain\File\Actions\DeleteFileAction;
use Domain\File\Actions\UploadByBase64Action;
use Domain\File\Actions\UploadByFormAction;
use Domain\File\Actions\UploadByUrlAction;
use Domain\File\Bags\FileBag;
use Domain\File\Repositories\FileRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class FileController extends Controller
{
    private UploadByBase64Action $uploadByBase64Action;
    private UploadByUrlAction $uploadByUrlAction;
    private UploadByFormAction $uploadByFormAction;
    private DeleteFileAction $deleteFileAction;
    private FileRepository $fileRepository;

    public function __construct(
        UploadByBase64Action $uploadByBase64Action,
        UploadByUrlAction $uploadByUrlAction,
        UploadByFormAction $uploadByFormAction,
        DeleteFileAction $deleteFileAction,
        FileRepository $fileRepository
    ) {
        $this->uploadByBase64Action = $uploadByBase64Action;
        $this->uploadByUrlAction = $uploadByUrlAction;
        $this->uploadByFormAction = $uploadByFormAction;
        $this->deleteFileAction = $deleteFileAction;
        $this->fileRepository = $fileRepository;
    }


    public function index()
    {
        $requestString = json_encode(request()->toArray());
        $cacheKey = 'files:index:'.md5($requestString);

        return Cache::remember($cacheKey, 8, function () {
            return FileResource::collection($this->fileRepository->all());
        });
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
