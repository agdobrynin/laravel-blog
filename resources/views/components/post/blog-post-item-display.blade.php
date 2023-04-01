<div>
    <div>{{ $post->title }}</div>
    <div>{{ $post->create_at  }}</div>
    <div> {{ $shortContent() }} </div>
    <div>
        <a href="{{ route('post.show', ['post' => $post->id]) }}">show</a>
        |
        <a href="{{ route('post.edit', ['post' => $post]) }}">edit</a>
        |
        <x-post.form-delete :$post />
    </div>
</div>
