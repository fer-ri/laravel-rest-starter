<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

abstract class AbstractRepository extends BaseRepository
{
    public function store($attributes)
    {
        $model = $this->model;

        foreach ($attributes as $key => $value) {
            $model->$key = $value;
        }

        $model->save();

        return $model;
    }

    public function findByUuid($uuid)
    {
        return $this->model->where('uuid', $uuid)->firstOrFail();
    }

    public function updateByUuid($uuid, $attributes)
    {
        $model = $this->findByUuid($uuid);

        foreach ($attributes as $key => $value) {
            $model->$key = $value;
        }

        $model->save();

        return $model;
    }
    
    public function destroyByUuid($uuid)
    {
        return $this->model->where('uuid', $uuid)->delete();
    }
}
