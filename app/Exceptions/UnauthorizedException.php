<?php


namespace App\Exceptions;

class UnauthorizedException extends BaseException
{
    protected $message = 'Unauthorized';

    protected $code = 401;

    protected string $link = "http://linktodoc.com";

    protected int $internalCode = 01;

    protected string $instructions = "Write instructions about this error";
}
