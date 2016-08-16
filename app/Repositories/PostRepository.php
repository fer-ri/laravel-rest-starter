<?php

namespace App\Repositories;

class PostRepository extends AbstractRepository
{
    public function model()
    {
        return \App\Models\Post::class;
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', $slug)->firstOrFail();
    }
}
