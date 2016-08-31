<?php

namespace App\Repositories;

class PostRepository extends AbstractRepository
{
    public function model()
    {
        return \App\Models\Post::class;
    }
}
