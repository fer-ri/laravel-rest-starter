<?php

namespace App\Repositories;

class CategoryRepository extends AbstractRepository
{
    public function model()
    {
        return \App\Models\Category::class;
    }
}
