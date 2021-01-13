<?php


namespace Domain\File\Exceptions;

use App\Exceptions\BaseException;

class DeleteFileException extends BaseException
{
    protected $message = 'Failure on delete file to cdn';

    protected $code = 404;

    protected string $link = "http://linktodoc.com";

    protected int $internalCode = 02;

    protected string $instructions = "Write instructions about this error";
}
