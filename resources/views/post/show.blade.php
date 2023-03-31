<x-app-layout :pageTitle="'Post: ' . $pageTitle">
    <div>{{ $post->title }}</div>
    <div>{{ $post->created_at }}</div>
    <div>{{ $post->content }}</div>
</x-app-layout>
