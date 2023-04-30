<x-mail::message>
### Привет {{ $user->name }}.

Новый комментарий под профилем пользователя {{ $comment->commentable->name }} на который Вы оставили свой комментарий:

<x-mail::button url="{{ route('users.show', [$comment->commentable]) }}">
    {{ $comment->commentable->name }}
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
