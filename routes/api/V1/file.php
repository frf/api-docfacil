<?php

use App\Api\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::prefix('files')
    ->group(function () {
        Route::get('/', [FileController::class, 'index'])
            ->name('files.list');
    });

Route::prefix('file')->middleware(['auth:api','user_context'])
    ->group(function () {

        Route::post('/upload/base64', [FileController::class, 'uploadBase64'])
            ->name('file.upload.base64');

        Route::post('/upload/url', [FileController::class, 'uploadUrl'])
            ->name('file.upload.url');

        Route::post('/upload/form', [FileController::class, 'uploadForm'])
            ->name('file.upload.form');

        Route::delete('/{id}', [FileController::class, 'destroy'])
            ->name('file.delete')
            ->where('id', '[0-9]+');
    });
