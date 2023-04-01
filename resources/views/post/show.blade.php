<x-app-layout pageTitle="Post: {{$pageTitle}}">
    <h4>{{ $post->title }}</h4>
    <div class="text-muted fw-lighter">
        {{ $post->created_at }} ({{ $post->created_at->diffForHumans() }})
    </div>
    <div class="mt-4 mb-4 border-top pt-4">
        {{ $post->content }}
    </div>

    <x-post.action :$post :showView="false"/>
</x-app-layout>
