<?php

namespace Domain\File\Bags;

use Cassandra\Uuid;

/**
 *
 * @property mixed file,
 * @property string name,
 * @property string type,
 * @property integer product_id,
 * @property integer user_id,
 * @property mixed metadata,
 */
class FileBag
{

    private $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public static function fromRequest(array $data)
    {
        if (!isset($data['metadata'])) {
            $data['metadata'] = null;
        }
        if (!isset($data['name'])) {
            $data['name'] = null;
        }

        return new self($data);
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
