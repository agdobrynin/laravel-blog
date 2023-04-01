<x-app-layout pageTitle='Latest posts'>
    <div class="row">
        @foreach($posts as $post)
            <x-post.display-short :$post class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4"/>
        @endforeach
    </div>
</x-app-layout>
