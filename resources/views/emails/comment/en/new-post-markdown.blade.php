@php use Illuminate\Support\Facades\URL; @endphp
@php use App\Enums\LocaleEnums; @endphp
@php URL::defaults(['locale' => LocaleEnums::EN->value]) @endphp
<x-mail::message>
### Hello {{ $comment->commentable->user->name }}.

New comment for your post:

<x-mail::button url="{{ route('posts.show', [$comment->commentable]) }}">
    {{ $comment->commentable->title }}
</x-mail::button>

Commentator
@if($comment->user)
    <a href="{{ route('users.show', [$comment->user]) }}">{{ $comment->user->name }}</a>
@else
    Anonymous
@endif
wroute:

<x-mail::panel>
<x-emails.user-avatar :fullOrigPath="$comment->user?->image?->fullOrigPath()"/>
{{ $comment->content }}
</x-mail::panel>


Sincerely yours,<br>
{{ config('app.name') }}
</x-mail::message>
