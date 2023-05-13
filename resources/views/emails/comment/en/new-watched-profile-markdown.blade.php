@php use Illuminate\Support\Facades\URL; @endphp
@php use App\Enums\LocaleEnums; @endphp
@php URL::defaults(['locale' => LocaleEnums::EN->value]) @endphp
<x-mail::message>
### Hello {{ $user->name }}.

New comment under the user profile {{ $comment->commentable->name }} to which you left your
a comment:
<x-mail::button url="{{ route('users.show', [$comment->commentable]) }}">
    {{ $comment->commentable->name }}
</x-mail::button>

Commentator
@if($comment->user)
    <a href="{{ route('users.show', [$comment->user]) }}">{{ $comment->user->name }}</a>
@else
    Anumymous
@endif
wroute:

<x-mail::panel>
    <x-emails.user-avatar :fullOrigPath="$comment->user?->image?->fullOrigPath()" />
    {{ $comment->content }}
</x-mail::panel>

Sincerely yours,<br>
{{ config('app.name') }}
</x-mail::message>
