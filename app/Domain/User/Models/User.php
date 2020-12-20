<?php

namespace Domain\User\Models;

use App\Domain\Tag\Models\HasTagsWithRank;
use App\Domain\User\Models\Scopes\AffiliatesTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $visible = [
        'name',
        'email',
        'mobile_phone',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
