@props([
    'post',
    'showView' => true,
])

<div class="d-flex gap-2" {{ $attributes }}>
    @if($showView)
        <a class="btn btn-sm btn-outline-primary" href="{{ route('post.show', [$post]) }}">show</a>
    @endif
    <a class="btn btn-sm btn-outline-secondary" href="{{ route('post.edit', [$post]) }}">edit</a>
    <form action="{{ route('post.destroy', [$post]) }}" method="POST" class="d-inline">
        <button type="submit" class="btn btn-sm btn-outline-danger">delete</button>
        @csrf
        @method('DELETE')
    </form>
</div>
