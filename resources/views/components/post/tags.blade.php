@props([
    'tags' => []
])

<div>
    <span class="fw-lighter">{{__('Теги поста:') }} </span>
    @foreach($tags as $tag)
        <a
            href="{{ route('post.index', ['tag' => $tag->id]) }}"
            {{ $attributes->merge(['class' => 'bg-primary badge']) }}
        >{{ $tag->name }}</a>
    @endforeach
</div>
