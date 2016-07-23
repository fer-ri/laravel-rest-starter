<?php

namespace App\Http\Controllers\API;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller as BaseController;

abstract class APIController extends BaseController
{
    use Helpers;
}
