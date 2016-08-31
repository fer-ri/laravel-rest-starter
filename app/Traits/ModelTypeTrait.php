<?php

namespace App\Traits;

use App\Models\ModelTypeScope;

trait ModelTypeTrait
{
    public static function bootModelTypeTrait()
    {
        static::addGlobalScope(new ModelTypeScope);

        self::creating(function ($model) {
            if (empty($model->type)) {
                $model->type = self::TYPE;
            }
        });
    }

    public function getType()
    {
        return defined('static::TYPE') ? static::TYPE : 'post';
    }
}
