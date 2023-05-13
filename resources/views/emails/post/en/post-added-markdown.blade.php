@php use Illuminate\Support\Facades\URL; @endphp
@php use App\Enums\LocaleEnums;use Illuminate\Support\Str; @endphp
@php URL::defaults(['locale' => LocaleEnums::EN->value]) @endphp
<x-mail::message>
    ### Hello {{ $user->name }}!

    New post was added:

    <x-mail::button url="{{ route('posts.show', [$post]) }}">
        {{ $post->title }}
    </x-mail::button>

    Author {{ $post->user->name }} wrote:
    <x-mail::panel>
        <x-emails.user-avatar :fullOrigPath="$post->user?->image?->fullOrigPath()"/>

        {{ Str::limit($post->content) }}


        <p style="font-size: 0.75em">
            Post tags:
            @foreach($post->tags as $tag)
                - {{ $tag->name }}
            @endforeach
        </p>
    </x-mail::panel>

    Sincerely yours,<br>
    {{ config('app.name') }}
</x-mail::message>
