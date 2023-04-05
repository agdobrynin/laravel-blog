<div {{ $attributes }}>
    <div class="card w-100">
        <div class="card-header">
            <h5 class="card-title">
                {{ $post->title }}
            </h5>
        </div>
        <div class="card-body">
            <p> {{ $shortContent() }} </p>
            <div class="text-muted fw-lighter">
                {{ $post->updated_at->diffForHumans()  }}

                @if($isUpdated())
                    <span class="badge rounded-pill bg-primary">
                        🔔 {{ __('обновлено') }}
                        <span class="visually-hidden">{{ __('обновлено') }}</span>
                    </span>
                @endif
            </div>
            <div class="text-muted fw-lighter">
                @if($post->comments_count)
                    {{ __('Есть комментарии') }}
                    <span class="badge rounded-pill bg-secondary">
                        💬 {{ $post->comments_count }}
                    </span>
                @else
                    {{ __('Комментариев пока нет.') }}
                @endif
            </div>
        </div>
        <div class="card-footer text-end">
            <x-post.action :$post/>
        </div>
    </div>
</div>
