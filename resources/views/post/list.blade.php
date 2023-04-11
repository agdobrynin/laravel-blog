<x-app-layout pageTitle='Latest posts'>
    <x-post.order-and-filter
        class="border rounded mx-0 my-4"
        :blogPostFilterDto="$filterDto"/>
    <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        @if($posts->count())
            @foreach($posts as $post)
                <x-post.display-short :$post class="col d-flex align-items-stretch"/>
            @endforeach
        @else
            <div class="col shadow-lg p-4 text-center">
                <h5 class="m-0">{{ __('Записей в блоге нет') }}</h5>
            </div>
        @endif
    </div>
</x-app-layout>
