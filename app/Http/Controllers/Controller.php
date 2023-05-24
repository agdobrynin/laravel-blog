<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes\Info;

#[Info(version: "1.0.1", title: "Blog post api")]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
