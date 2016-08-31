<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ModelTypeScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereType($model->getType());
    }
}
