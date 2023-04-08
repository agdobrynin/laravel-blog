<div {{ $attributes }}>
    <div class="card w-100">
        <div class="card-header">
            <h5 class="card-title">
                {{ $post->title }}
            </h5>
        </div>
        <div class="card-body">
            <p> {{ $shortContent() }} </p>
            <p>{{ __('Автор') }}: {{ $post->user->name }}</p>
        </div>
        <div class="card-footer text-muted">
            <div class="d-flex justify-content-between gap-4">
                <div class="text-start">
                    {{ $post->updated_at->diffForHumans()  }}
                    @if($isUpdated())
                        <span class="badge rounded-pill bg-primary">
                        ✏
                        <span class="visually-hidden">{{ __('обновлено') }}</span>
                    </span>
                    @endif
                </div>
                <div class="text-end">
                    <span class="badge rounded-pill {{$post->comments_count ? 'bg-success' : 'bg-secondary'}}">
                        💬 {{ $post->comments_count }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <x-post.action :$post/>
        </div>
    </div>
</div>
