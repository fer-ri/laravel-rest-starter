<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use UuidTrait, Sluggable, SoftDeletes;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
                'includeTrashed' => true,
            ],
        ];
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if ($model->status == 'published' && empty($model->published_at)) {
                $model->published_at = new \DateTime;
            }
        });
    }
}
