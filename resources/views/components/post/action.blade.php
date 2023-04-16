@props([
    'post',
])

<div class="d-flex gap-2" {{ $attributes }}>
    @if ($post->trashed())
        @canany(['restore'], $post)
            <form action="{{ route('posts.restore', [$post]) }}" method="POST" class="d-inline">
                <button type="submit" class="btn btn-sm btn-outline-warning">{{ __('восстановить') }}</button>
                @csrf
                @method('PUT')
            </form>
        @endcan
    @else
        @canany(['update', 'delete'], $post)
            <a class="btn btn-sm btn-outline-secondary"
               href="{{ route('posts.edit', [$post]) }}">{{ __('редактировать') }}</a>
            <form action="{{ route('posts.destroy', [$post]) }}" method="POST" class="d-inline">
                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('удалить') }}</button>
                @csrf
                @method('DELETE')
            </form>
        @endcan
    @endif
</div>
