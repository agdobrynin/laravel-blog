<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel App @isset($pageTitle) | {{  $pageTitle }}@endisset</title>
</head>
<body>
<div>
    <a href="{{ route('home.index') }}">Home</a>
    |
    <a href="{{ route('post.index') }}">Posts</a>
    |
    <a href="{{ route('post.create') }}">New post</a>
</div>
<hr>
@if(session('error'))
    <x-ui.alert type="error" class="m-4">
        {{ session('error') }}
    </x-ui.alert>
@endif
@if(session('success'))
    <x-ui.alert type="success" class="m-4">
        {{ session('success') }}
    </x-ui.alert>
@endif

{{ $slot }}
</body>
</html>
