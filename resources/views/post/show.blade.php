<x-app-layout pageTitle="{{ __('Пост') }}: {{$pageTitle}}">
    <div class="border rounded shadow-sm p-3">
        <h4>{{ $post->title }}</h4>
        <div class="text-muted fw-lighter">
            {{ __('Создано') }}: {{ $post->created_at }} ({{ $post->created_at->diffForHumans() }})
            @if($post->created_at != $post->updated_at)
                / {{ __('последнее обновление') }} {{ $post->updated_at->diffForHumans() }}
            @endif
            .
            {{ __('Автор') }} {{ $post->user->name }}
        </div>
        <div class="mt-4 mb-4 pt-4 text-wrap">
            {{ $post->content }}
        </div>

        <x-post.action :$post :showView="false"/>
    </div>

    <h4 class="mt-4">{{ __('Комментарии') }}</h4>
    @forelse($post->comments as $comment)
        <x-comment.item :$comment />
    @empty
        <p class="my-3 p-3 border rounded shadow-sm">{{ __('Пока комментариев нет.') }}</p>
    @endforelse
</x-app-layout>
