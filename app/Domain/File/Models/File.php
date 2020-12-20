<?php

namespace Domain\File\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const TYPE_DOC = 'doc';
    public const TYPE_PROFILE_PICTURE = 'profile_picture';

}
