@props([
    'comments',
])

<h4 class="mt-4">{{ __('Комментарии') }}</h4>
@if($comments->hasPages())
    <div>{{ $comments->links() }}</div>
@endif
@forelse($comments as $comment)
    <x-comment.item :$comment/>
@empty
    <p class="my-3 p-3 border rounded shadow-sm">{{ __('Пока комментариев нет.') }}</p>
@endforelse
@if($comments->hasPages())
    <div class="pt-4">{{ $comments->links() }}</div>
@endif
