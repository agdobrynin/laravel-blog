<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel App @isset($pageTitle)
            | {{  $pageTitle }}
        @endisset</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="d-flex flex-column flex-md-row align-content-center p-3 px-md-4 bg-white border-bottom shadow-sm mb-3">
    <h5 class="my-0 me-md-auto">
        <a href="{{ route('home.index') }}" class="text-dark text-decoration-none">Laravel App</a>
    </h5>
    <nav class="my-2 my-md-0 me-md-3">
        <a href="{{ route('post.index') }}" class="p-2 text-dark">Posts</a>
        <a href="{{ route('post.create') }}" class="p-2 text-dark">New post</a>
    </nav>
</div>
<div class="container mb-4">
    @if(session('error'))
        <x-ui.alert type="dander">
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
