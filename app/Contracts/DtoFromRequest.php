<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface DtoFromRequest
{
    public static function fromRequest(Request $request): self;
}
