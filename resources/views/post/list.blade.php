<x-app-layout pageTitle='Latest posts'>
    @if($posts->count())
        <x-post.order-and-filter
            class="border rounded mx-0 my-4"
            :blogPostFilterDto="$filterDto"/>
    @endif
    <div class="row">
        <div class="col-12 @if($mostActiveBloggers->bloggers->count()) col-lg-8 col-xl-9 @endif order-lg-0 order-1">
            <div class="row justify-content-center row-cols-1 row-cols-lg-2 row-cols-xl-3 g-4">
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
        </div>

        @if($mostActiveBloggers->bloggers->count())
            <div class="col-12 col-lg-4 col-xl-3 order-lg-2 order-0">
                <x-ui.card title="{{ trans('Самые активные блогеры') }}" class="border-info">
                    <x-slot:subtitle>
                        @if($mostActiveBloggers->minCountPost)
                            {{ __('опубликовавшие :count постов и более', ['count' => $mostActiveBloggers->minCountPost]) }}
                        @endif
                        @if($mostActiveBloggers->lastMonth)
                            {{ __('за последние :month месяцев', ['month' => $mostActiveBloggers->lastMonth]) }}
                        @endif
                    </x-slot:subtitle>
                    <x-slot:items>
                        @foreach($mostActiveBloggers->bloggers as $blogger)
                            <li class="list-group-item">
                                <span class="fw-bold">{{ $blogger->name }}</span>
                                , опубликовал <span class="badge bg-info">{{ $blogger->blog_posts_count }}</span> постов
                            </li>
                        @endforeach
                    </x-slot:items>
                </x-ui.card>
            </div>
        @endif

    </div>
</x-app-layout>
