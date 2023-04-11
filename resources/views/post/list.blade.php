<x-app-layout pageTitle='Latest posts'>
    <x-post.order-and-filter
        class="border rounded mx-0 my-4"
        :blogPostFilterDto="$filterDto"/>
    <div class="row">
        <div class="col-12 @if($mostActiveBloggers) col-lg-8 col-xl-9 @endif order-lg-0 order-1">
            <div class="row justify-content-center row-cols-1 row-cols-lg-2 row-cols-xl-3 g-4">
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
        </div>
        @if($mostActiveBloggers)
            <div class="col-12 col-lg-4 col-xl-3 order-lg-2 order-0">
                <x-blogger.most-active class="mb-4" :$mostActiveBloggers />
            </div>
        @endif
    </div>
</x-app-layout>
