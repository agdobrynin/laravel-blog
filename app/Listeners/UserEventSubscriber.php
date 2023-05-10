<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserEventSubscriber
{
    public function __construct(protected Request $request)
    {
    }

    public function handleUserLogin(Login $event): void
    {
        if ($locale = $event->user?->preference?->locale) {
            Session::put('locale', $locale);
        }
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleUserLogin'
        ];
    }
}
