<?php

declare(strict_types=1);

namespace App\Dto\Request\Api;

use App\Contracts\DtoFromRequest;
use Illuminate\Http\Request;

readonly class LoginDto implements DtoFromRequest
{
    public function __construct(public string $email, public string $password, public string $device)
    {
    }

    public static function fromRequest(Request $request): static
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device' => 'required',
        ]);

        return new static($request->input('email'), $request->input('password'), $request->input('device'));
    }
}
