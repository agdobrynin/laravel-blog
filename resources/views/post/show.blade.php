<x-app-layout pageTitle="Post: {{$pageTitle}}">
    <div class="border rounded shadow-sm p-3">
        <h4>{{ $post->title }}</h4>
        <div class="text-muted fw-lighter">
            Created: {{ $post->created_at }} ({{ $post->created_at->diffForHumans() }})
            @if($post->created_at != $post->updated_at)
                / last update {{ $post->updated_at->diffForHumans() }}
            @endif
        </div>
        <div class="mt-4 mb-4 pt-4">
            {{ $post->content }}
        </div>
        <hr>
        <x-post.action :$post :showView="false"/>
    </div>

    <h4 class="mt-4">Comments</h4>
    @forelse($post->comments as $comment)
        <x-comment.item :$comment />
    @empty
        <p class="my-3 p-3 border rounded shadow-sm">No comments yet.</p>
    @endforelse
</x-app-layout>
