<?php

namespace App\Models;

use App\Traits\UuidTrait;
use App\Traits\RoleTrait;
use App\Traits\PermissionTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use UuidTrait, SoftDeletes, RoleTrait, PermissionTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_code = str_random(30);
        });
    }

    /**
     * This mutator automatically hashes the password.
     *
     * @var string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = app('hash')->needsRehash($value)
            ? bcrypt($value)
            : $value;
    }
}
