<?php

namespace App\Traits;

use Uuid;

trait UuidTrait
{
    /**
     * The "booting" method of the model.
     */
    public static function bootUuidTrait()
    {
        static::creating(function ($model) {
            $key = 'uuid';

            if (empty($model->$key)) {
                $model->$key = (string) $model->generateUuid();
            }
        });
    }

    public function generateUuid()
    {
        return Uuid::generate(4);
    }
}
