<x-mail::message>
### Привет {{ $comment->commentable->user->name }}.

Новый комментарий на Ваш пост:

<x-mail::button url="{{ route('posts.show', [$comment->commentable]) }}">
    {{ $comment->commentable->title }}
</x-mail::button>

Комментатор
@if($comment->user)
<a href="{{ route('users.show', [$comment->user]) }}">{{ $comment->user->name }}</a>
@else
{{ trans('Аноним') }}
@endif
написал:

<x-mail::panel>
    @if($comment->user?->image?->fullOrigPath())
        <img class="avatar"
             alt="{{ __('Аватар пользователя') }}"
             src="file://{{ $comment->user->image->fullOrigPath() }}"/>
    @else
        <img class="avatar"
             alt="{{ __('Аватар пользователя') }}"
             src="file://{{ resource_path('/images/unicorn-icon-svgrepo-com.svg') }}"/>
    @endif
    {{ $comment->content }}
</x-mail::panel>

{{ __('Спасибо ваш') }},<br>
{{ config('app.name') }}
</x-mail::message>
