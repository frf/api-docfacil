<?php

namespace Domain\User\Bags;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserBag
{
    private array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public static function fromRequest($attributes)
    {
        return new self($attributes);
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function __set($name, $value)
    {
        return $this->attributes[$name] = $value;
    }
}
