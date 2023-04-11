@props([
    'mostActiveBloggers'
])
<div {{ $attributes->merge(['class' => '']) }}>
    <div class="card border-info w-100">
        <div class="card-header">
            <h5 class="card-title">
                {{ __('Самые активные блогеры') }}
            </h5>
        </div>
        <div class="card-body">
            <h6 class="card-subtitle">
                @if($mostActiveBloggers->minCountPost)
                    {{ __('опубликовавшие :count постов и более', ['count' => $mostActiveBloggers->minCountPost]) }}
                @endif
                @if($mostActiveBloggers->lastMonth)
                    {{ __('за последние :month месяцев', ['month' => $mostActiveBloggers->lastMonth]) }}
                @endif
            </h6>
        </div>
        <ul class="list-group list-group-flush">
            @foreach($mostActiveBloggers->bloggers as $blogger)
            <li class="list-group-item">
                <span class="fw-bold">{{ $blogger->name }}</span>
                , опубликовал <span class="badge bg-info">{{ $blogger->blog_posts_count }}</span> постов
            </li>
            @endforeach
        </ul>
    </div>
</div>
