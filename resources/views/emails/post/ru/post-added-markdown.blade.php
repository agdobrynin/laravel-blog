@php use Illuminate\Support\Facades\URL; @endphp
@php use App\Enums\LocaleEnums;use Illuminate\Support\Str; @endphp
@php URL::defaults(['locale' => LocaleEnums::RU->value]) @endphp
<x-mail::message>
### Привет {{ $user->name }}!

На сайт добавлен новый пост:

<x-mail::button url="{{ route('posts.show', [$post]) }}">{{ $post->title }}</x-mail::button>

Автор {{ $post->user->name }} написал:
<x-mail::panel>
<x-emails.user-avatar :fullOrigPath="$post->user?->image?->fullOrigPath()"/>

{{ Str::limit($post->content) }}


<p style="font-size: 0.75em">
Тэги поста:
@foreach($post->tags as $tag)
- {{ $tag->name }}
@endforeach
</p>
</x-mail::panel>

С уважением Ваш,<br>
{{ config('app.name') }}
</x-mail::message>
