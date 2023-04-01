<x-app-layout pageTitle='Latest posts'>
    <div class="row">
        @if($posts->count())
            @foreach($posts as $post)
                <x-post.display-short :$post class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4"/>
            @endforeach
        @else
            <div class="col-12 col-md-6 text-center offset-md-3 shadow-lg p-2"><h5>Posts not found</h5></div>
        @endif
    </div>
</x-app-layout>
