<x-app-layout pageTitle="{{ __('Пост') }}: {{$pageTitle}}">
    <div class="row">
        <div class="col-12 @if($mostActiveBloggerDto->bloggers->count()) col-md-8 col-xl-9 @endif">
            <div class="border rounded shadow-sm p-3">
                <h4>{{ $post->title }}</h4>
                <div class="text-muted fw-light">
                    <x-user.author-and-date
                        :user="$post['user']"
                        :avatarSize="48"
                        class="shadow-sm"
                        :created_at="$post['created_at']"
                        :updated_at="$post['updated_at']" />
                </div>
                @if(!empty($readCount))
                    <div class="text-muted fw-lighter">
                        {{ trans_choice('{1} Сейчас читает :count пользователь|[2,19] Сейчас читает :count пользователей', $readCount) }}
                    </div>
                @endif
                @if($post->tags->count())
                    <div class="text-lowercase pt-0 fw-lighter">
                        {{ __('Тэги поста:') }}
                        <x-info.tags
                                :tags="$post['tags']"
                                routeName="posts.index"
                                class="bg-success fw-lighter text-light"/>
                    </div>
                @endif
                <div class="mt-0 mb-4 pt-4">
                    @if($image = $post->image)
                        <img src="{{ $image->thumbUrl(950) }}" class="img-fluid w-100 mb-4 img-thumbnail" alt="Image">
                    @endif
                    <div style="white-space: pre-wrap;">{{ $post->content }}</div>
                </div>
                @auth
                    <x-post.action :$post/>
                @endauth
            </div>
        </div>
        @if($mostActiveBloggerDto->bloggers->count())
            <div class="col-12 col-md-4 col-xl-3 d-sm-none d-md-inline">
                <x-info.most-active-bloggers :$mostActiveBloggerDto />
            </div>
        @endif
    </div>

    <h4 class="mt-4 text-secondary">{{ __('Добавить комментарий к публикации') }}</h4>
    <x-comment.form
        action="{{ route('posts.comments.store', $post) }}"
        class="border rounded p-3 shadow-sm"/>
    <x-comment.list-with-pagination :$comments/>
</x-app-layout>
