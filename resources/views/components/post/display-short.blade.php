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
                <small>
                    {{ $post->updated_at->diffForHumans()  }}

                    @if($isUpdated())
                        <span class="badge text-bg-secondary">🔔 updated</span>
                    @endif
                </small>
            </div>
        </div>
        <div class="card-footer text-end">
            <x-post.action :$post/>
        </div>
    </div>
</div>
