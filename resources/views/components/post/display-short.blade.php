<div {{ $attributes }}>
    <div class="card w-100">
        <div class="card-header">
            <h5 class="card-title">
                <a href="{{ route('post.show', [$post]) }}">{{ $post->title }}</a>
            </h5>
        </div>
        <div class="card-body">
            <p> {{ $shortContent() }} </p>
        </div>
        <div class="card-footer text-muted">
            <div class="d-flex justify-content-between gap-4">
                <div class="text-start">
                    <x-ui.author-and-date
                        :name="$post['user']['name']"
                        :created_at="$post['created_at']"
                        :updated_at="$post['updated_at']"
                    />
                </div>
                <div class="text-end">
                    <span class="badge rounded-pill {{$post->comments_count ? 'bg-success' : 'bg-secondary'}}">
                        💬 {{ $post->comments_count }}
                    </span>
                </div>
            </div>
        </div>
        @auth
            <div class="card-footer text-end">
                <x-post.action :$post/>
            </div>
        @endauth
    </div>
</div>
