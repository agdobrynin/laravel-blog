<x-app-layout pageTitle="Post: {{$pageTitle}}">
    <div>{{ $post->title }}</div>
    <div>{{ $post->created_at }}</div>
    <div>{{ $post->content }}</div>
    <hr>
    <a href="{{ route('post.edit', [$post]) }}">edit</a>
    |
    <x-post.form-delete :$post />
</x-app-layout>
