<div {{ $attributes }}>
    <h4>{{ $post->title }}</h4>
    <div class="text-muted fw-lighter">{{ $post->created_at->diffForHumans()  }}</div>
    <p> {{ $shortContent() }} </p>
    <x-post.action :$post/>
</div>
