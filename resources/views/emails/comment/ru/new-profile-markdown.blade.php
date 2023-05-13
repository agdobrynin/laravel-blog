@php use Illuminate\Support\Facades\URL; @endphp
@php use App\Enums\LocaleEnums; @endphp
@php URL::defaults(['locale' => LocaleEnums::RU->value]) @endphp
<x-mail::message>
### Привет {{ $comment->commentable->name }}.

Новый комментарий под вашим профилем:
<x-mail::button url="{{ route('users.show', [$comment->commentable]) }}">
    Мой профиль
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
