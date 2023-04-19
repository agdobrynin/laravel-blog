@props([
    'comments',
    'action',
    'title' => trans('Добавить комментарий'),
])
<h4 class="mt-4 text-secondary">{{ $title }}</h4>
<x-comment.form :$action class="border rounded p-3 shadow-sm"/>
<h4 class="mt-4">{{ __('Комментарии') }}</h4>
@if($comments->hasPages())
    <div>{{ $comments->onEachSide(3)->links() }}</div>
@endif
@forelse($comments as $comment)
    <x-comment.item :$comment/>
@empty
    <p class="my-3 p-3 border rounded shadow-sm">{{ __('Пока комментариев нет.') }}</p>
@endforelse
@if($comments->hasPages())
    <div class="pt-4">{{ $comments->onEachSide(3)->links() }}</div>
@endif
