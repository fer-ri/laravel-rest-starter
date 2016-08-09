<?php

namespace App\Http\Controllers\API;

class UserController extends APIController
{
    public function me()
    {
        \Log::debug($this->user);

        return $this->user;
    }
}
