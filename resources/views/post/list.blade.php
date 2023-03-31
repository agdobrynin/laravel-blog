<x-app-layout pageTitle='Latest posts'>
    @foreach($posts as $post)
        <x-post.blog-post-item-display :$post/>
    @endforeach
</x-app-layout>
