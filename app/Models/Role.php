<?php

namespace App\Models;

use App\Traits\UuidTrait;
use App\Traits\PermissionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use UuidTrait, SoftDeletes, PermissionTrait;

    protected $fillable = ['name', 'display_name', 'description', 'permissions', 'level'];

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class);
    }
}
