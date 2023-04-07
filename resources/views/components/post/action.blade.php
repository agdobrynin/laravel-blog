@props([
    'post',
    'showView' => true,
])

<div class="d-flex gap-2" {{ $attributes }}>
    @if($showView)
        <a class="btn btn-sm btn-outline-primary" href="{{ route('post.show', [$post]) }}">{{ __('показать') }}</a>
    @endif
    @canany(['update', 'delete'], $post)
        <a class="btn btn-sm btn-outline-secondary"
           href="{{ route('post.edit', [$post]) }}">{{ __('редактировать') }}</a>
        <form action="{{ route('post.destroy', [$post]) }}" method="POST" class="d-inline">
            <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('удалить') }}</button>
            @csrf
            @method('DELETE')
        </form>
    @endcan
</div>
