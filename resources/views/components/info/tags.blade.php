@props([
    'tags' => [],
    'routeName' => null,
])

@foreach($tags as $tag)
    <a @if($routeName) href="{{ route($routeName, ['tag' => $tag->id]) }}" @endif
            {{ $attributes->merge(['class' => 'bg-primary badge']) }}>{{ $tag->name }}</a>
@endforeach
