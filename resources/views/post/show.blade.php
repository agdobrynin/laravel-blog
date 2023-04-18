<x-app-layout pageTitle="{{ __('Пост') }}: {{$pageTitle}}">
    <div class="row">
        <div class="col-12 @if($mostActiveBloggerDto->bloggers->count()) col-md-8 col-xl-9 @endif">
            <div class="border rounded shadow-sm p-3">
                <h4>{{ $post->title }}</h4>
                <div class="text-muted fw-light">
                    <x-user.author-and-date
                        :user="$post['user']"
                        :avatarSize="48"
                        :created_at="$post['created_at']"
                        :updated_at="$post['updated_at']" />
                </div>
                @if(!empty($readCount))
                    <div class="text-muted fw-lighter">
                        {{ trans_choice('{1} Сейчас читает :count пользователь|[2,19] Сейчас читает :count пользователей', $readCount) }}
                    </div>
                @endif
                @if($post->tags->count())
                    <div class="text-lowercase pt-0">
                        <x-post.tags :tags="$post['tags']" class="bg-success fw-lighter text-light"/>
                    </div>
                @endif
                <div class="mt-0 mb-4 pt-4">
                    @if($image = $post->image)
                        <img src="{{ $image->url() }}" class="img-fluid w-100 mb-4 img-thumbnail">
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

    <h4 class="mt-4">{{ __('Комментарии') }}</h4>
    <x-comment.form :$post class="border rounded p-3 shadow-sm"/>
    @if($comments->hasPages())
        <div class="pt-4">{{ $comments->onEachSide(3)->links() }}</div>
    @endif
    @forelse($comments as $comment)
        <x-comment.item :$comment />
    @empty
        <p class="my-3 p-3 border rounded shadow-sm">{{ __('Пока комментариев нет.') }}</p>
    @endforelse
    @if($comments->hasPages())
        <div class="pt-4">{{ $comments->onEachSide(3)->links() }}</div>
    @endif
</x-app-layout>
