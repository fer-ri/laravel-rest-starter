<?php

namespace App\Models;

use App\Traits\UuidTrait;
use App\Traits\ModelTypeTrait;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use UuidTrait, ModelTypeTrait, NodeTrait, SoftDeletes, Sluggable;

    const TYPE = 'post';

    protected $table = 'categories';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
