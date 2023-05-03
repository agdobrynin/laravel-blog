<x-mail::message>
### Привет!

На сайт добавлен новый пост:

<x-mail::button url="{{ route('posts.show', [$post]) }}">
    {{ $post->title }}
</x-mail::button>

Автор {{ $post->user->name }} написал:
<x-mail::panel>
    @if($post->user?->image?->fullOrigPath())
        <img class="avatar" alt="Аватар пользователя" src="file://{{ $post->user->image->fullOrigPath() }}"/>
    @else
        <img class="avatar" alt="Аватар пользователя" src="file://{{ resource_path('/images/unicorn-icon-svgrepo-com.svg') }}"/>
    @endif


{{ \Illuminate\Support\Str::limit($post->content) }}


Тэги поста:
@foreach($post->tags as $tag)
- {{ $tag->name }}
@endforeach
</x-mail::panel>

{{ __('Спасибо ваш') }},
{{ config('app.name') }}
</x-mail::message>
