@props([
    'items',
    'title',
    'subtitle' => null,
    'footer' => null,
])
<div {{ $attributes->merge(['class' => 'card w-100']) }}>
    <div class="card-header">
        <h5 class="card-title">{{ $title }}</h5>
    </div>

    @if(!empty(trim($subtitle)))
        <div class="card-body"> {{ $subtitle }}</div>
    @endif

    <ul class="list-group list-group-flush">
        @if(\is_a($items, 'Illuminate\Support\Collection'))
            @foreach($items as $item)
                <li class="list-group-item">{{ $item }}</li>
            @endforeach
        @else
            {{ $items }}
        @endif
    </ul>
    @if(!empty(trim($footer)))
        <div class="card-footer">
            <small class="text-muted"> {{ $footer }} </small>
        </div>
    @endif
</div>
