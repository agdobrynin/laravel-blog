<x-app-layout pageTitle='Latest posts'>
    @if($posts->count())
        <x-post.order-and-filter
            class="border rounded mx-0 my-4"
            :blogPostFilterDto="$filterDto"
            :$tags
        />
    @endif
    <div class="row">
        <div class="col-12 @if($mostActiveBloggerDto->bloggers->count()) col-lg-8 col-xl-9 @endif order-lg-0 order-1">
            <div class="col-12">
                {{ $posts->onEachSide(3)->links() }}
            </div>
            <div class="row justify-content-center row-cols-1 row-cols-lg-2 row-cols-xl-3 g-4 mb-4">
                @if($posts->count())
                    @foreach($posts as $post)
                        @if($post->trashed()) <del class="text-muted"> @endif
                        <x-post.display-short :$post class="col d-flex align-items-stretch"/>
                        @if($post->trashed()) </del> @endif
                    @endforeach
                @else
                    <div class="col shadow-lg p-4 text-center">
                        <h5 class="m-0">{{ __('Записей в блоге нет') }}</h5>
                    </div>
                @endif
            </div>
            <div class="col-12">
                {{ $posts->onEachSide(3)->links() }}
            </div>
        </div>

        @if($mostActiveBloggerDto->bloggers->count())
            <div class="col-12 col-lg-4 col-xl-3 order-lg-2 order-0 mb-4">
                <x-info.most-active-bloggers :$mostActiveBloggerDto />
            </div>
        @endif
    </div>
</x-app-layout>
