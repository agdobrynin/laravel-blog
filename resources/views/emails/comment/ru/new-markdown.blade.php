<style>
    .avatar {
        vertical-align: middle;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin: 0em 1em 1em 0em;
        float: left;
        border: 1px solid gray;
        box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.2);
    }
</style>
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
    @if($comment->user?->image)
        <img class="avatar"
             alt="{{ __('Аватар пользователя') }}"
             src="file://{{ $comment->user->image->fullPath() }}"/>
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
