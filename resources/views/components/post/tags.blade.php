@props([
    'tags' => []
])

<div>
    <span class="fw-lighter">{{__('Теги поста:') }} </span>
    @foreach($tags as $tag)
        <span {{ $attributes->merge(['class' => 'bg-primary badge']) }}>{{ $tag->name }}</span>
    @endforeach
</div>
