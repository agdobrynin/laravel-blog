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
                        🔔 updated
                        <span class="visually-hidden">was updated</span>
                    </span>
                @endif
            </div>
            <div class="text-muted fw-lighter">
                @if($post->comments_count)
                    Has comments
                    <span class="badge rounded-pill bg-secondary">
                        💬 {{ $post->comments_count }}
                    </span>
                @else
                    No comments yet.
                @endif
            </div>
        </div>
        <div class="card-footer text-end">
            <x-post.action :$post/>
        </div>
    </div>
</div>
