@props([
    'route',
    'actionTitle',
    'post' => null,
    'method' => 'POST',
])

<form action="{{ $route }}" method="post">
    @csrf
    @method($method)
    <x-ui.input name="title" label="Title" class="input-small" value="{{ old('title', $post->title ?? '') }}"/>
    <x-ui.textarea name="content" label="Post content" value="{{ old('content', $post->content ?? '') }}"/>
    <input type="submit" value="{{ $actionTitle }}">
</form>
