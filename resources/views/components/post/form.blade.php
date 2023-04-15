@props([
    'route',
    'actionTitle',
    'post' => null,
    'method' => 'POST',
])

@inject('tagsDictionary', App\Services\Contracts\TagsDictionaryInterface::class)

<form action="{{ $route }}" method="post" {{ $attributes }}>
    @csrf
    @method($method)
    <x-ui.input name="title" label="{{ __('Название') }}" class="input-small" value="{{ old('title', $post->title ?? '') }}"/>
    <x-ui.textarea name="content" label="{{ __('Содержание поста') }}" rows="5" value="{{ old('content', $post->content ?? '') }}"/>
    <x-ui.select
        name="tags"
        label="{{ __('Тэги для поста') }}"
        :multiple="true"
        :values="$tagsDictionary->tagsForForm()"
        :data="old('tags', $post?->tags->pluck('id')->toArray())"
    />

    <input type="submit" class="btn btn-primary w-100" value="{{ $actionTitle }}">

</form>
