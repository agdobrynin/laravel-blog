@props([
    'route',
    'actionTitle',
    'post' => null,
    'method' => 'POST',
])

<form action="{{ $route }}" method="post" {{ $attributes }}>
    @csrf
    @method($method)
    <x-ui.input name="title" label="{{ __('Название') }}" class="input-small" value="{{ old('title', $post->title ?? '') }}"/>
    <x-ui.textarea name="content" label="{{ __('Содержание поста') }}" rows="5" value="{{ old('content', $post->content ?? '') }}"/>
    <input type="submit" class="btn btn-primary w-100" value="{{ $actionTitle }}">
</form>
