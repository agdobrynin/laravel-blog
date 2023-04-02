<div {{ $attributes }}>
    <div class="card w-100">
        <div class="card-header">
            <h5 class="card-title">
                {{ $post->title }}
            </h5>
        </div>
        <div class="card-body">
            <p> {{ $shortContent() }} </p>
            <div class="text-muted fw-lighter position-relative">
                {{ $post->updated_at->diffForHumans()  }}

                @if($isUpdated())
                    <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-primary">
                        🔔 updated
                        <span class="visually-hidden">was updated</span>
                    </span>
                @endif
            </div>
        </div>
        <div class="card-footer text-end">
            <x-post.action :$post/>
        </div>
    </div>
</div>
