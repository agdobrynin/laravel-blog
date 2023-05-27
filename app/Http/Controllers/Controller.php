<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes\{Info, OpenApi, SecurityScheme, Server};

#[OpenApi(
    info: new Info(version: '1.0.1', title: 'Blog post api'),
    servers: [
        new Server(url: '/api', description: 'API main endpoint'),
    ],
)]
#[SecurityScheme(
    securityScheme: 'apiKeyBearer',
    type: 'http',
    description: 'Bearer token authorization',
    name: 'Authorization',
    in: 'header',
    bearerFormat: 'string',
    scheme: 'bearer',
)]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
