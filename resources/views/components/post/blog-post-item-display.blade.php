<div>
    <div>{{ $post->title }}</div>
    <div>{{ $post->create_at  }}</div>
    <div> {{ $shortContent() }} </div>
    <div><a href="{{ route('post.show', ['post' => $post->id]) }}">show</a></div>
</div>
