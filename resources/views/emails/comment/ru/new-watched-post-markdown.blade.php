@php use Illuminate\Support\Facades\URL; @endphp
@php use App\Enums\LocaleEnums; @endphp
@php URL::defaults(['locale' => LocaleEnums::RU->value]) @endphp
<x-mail::message>
### Привет {{ $user->name }}.

Новый комментарий на пост под которым Вы оставил свой комментарий:
<x-mail::button url="{{ route('posts.show', [$comment->commentable]) }}">
    {{ $comment->commentable->title }}
</x-mail::button>

Комментатор
@if($comment->user)
    <a href="{{ route('users.show', [$comment->user]) }}">{{ $comment->user->name }}</a>
@else
    Аноним
@endif
написал:
<x-mail::panel>
<x-emails.user-avatar :fullOrigPath="$comment->user?->image?->fullOrigPath()" />
{{ $comment->content }}
</x-mail::panel>

С уважением Ваш,<br>
{{ config('app.name') }}
</x-mail::message>
