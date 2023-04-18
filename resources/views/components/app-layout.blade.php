<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel App @isset($pageTitle) | {{  $pageTitle }}@endisset</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img
                src="{{ Vite::asset('resources/images/post-office-svgrepo-com.svg') }}"
                height="40"
                alt="{{ config('app.name', 'Laravel') }}"
            >
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.index') }}">{{ __('Записи в блоге') }}</a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.create') }}">{{ __('Новый пост') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{Auth::user()->name}}
                            @role(App\Enums\RolesEnum::ADMIN)
                                👑
                            @else
                                🎠
                            @endrole
                            <x-user.avatar :user="Auth::user()" class="shadow-sm" :size="36"/>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-0">
                            <li><a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">{{ __('Профиль') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a
                                    class="dropdown-item"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    href="{{ route('logout') }}">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
<div class="container mb-5">
    @if(session('error'))
        <x-ui.alert type="danger">
            {{ session('error') }}
        </x-ui.alert>
    @endif
    @if(session('success'))
        <x-ui.alert type="success">
            {{ session('success') }}
        </x-ui.alert>
    @endif

    {{ $slot }}
</div>
</body>
</html>
