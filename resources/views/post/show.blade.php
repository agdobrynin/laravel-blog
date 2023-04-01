<x-app-layout pageTitle="Post: {{$pageTitle}}">
    <h4>{{ $post->title }}</h4>
    <div class="text-muted fw-lighter">
        Created: {{ $post->created_at }} ({{ $post->created_at->diffForHumans() }})
        @if($post->created_at != $post->updated_at)
            / last update {{ $post->updated_at->diffForHumans() }}
        @endif
    </div>
    <div class="mt-4 mb-4 border-top pt-4">
        {{ $post->content }}
    </div>

    <x-post.action :$post :showView="false"/>
</x-app-layout>
