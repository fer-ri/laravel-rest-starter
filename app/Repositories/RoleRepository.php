<?php

namespace App\Repositories;

class RoleRepository extends AbstractRepository
{
    public function model()
    {
        return \App\Models\Role::class;
    }
}
