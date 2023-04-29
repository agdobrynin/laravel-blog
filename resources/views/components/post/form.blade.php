@props([
    'route',
    'actionTitle',
    'post' => null,
    'method' => 'POST',
])

@inject('tagsDictionary', App\Services\Contracts\TagsDictionaryInterface::class)

<form action="{{ $route }}"
      method="post"
      enctype="multipart/form-data"
    {{ $attributes->merge(['class' => '']) }}>
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
    <div class="row mb-4">
        <div class="col-12 col-md-6 text-center img-thumbnail">
            @if($post?->image)
                <img src="{{ $post->image->url() }}" alt="Blog post image" class="img-fluid w-100"/>
            @else
                {{ __('Без картинки') }}
            @endif
        </div>
        <div class="col-12 col-md-6">
            <x-ui.input name="thumb"
                        type="file"
                        label="{{ __('Картинка') }}"
                        class="input-small"/>
            @if($post?->image)
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="delete_image" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">{{ __('Удалить картинку') }}</label>
                </div>
            @endif
        </div>
    </div>

    <input type="submit" class="btn btn-primary w-100" value="{{ $actionTitle }}">

</form>
