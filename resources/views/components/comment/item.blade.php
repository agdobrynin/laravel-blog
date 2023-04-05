@props(['comment'])
<div class="border rounded shadow-sm p-3 my-3">
    <div class="fw-lighter"><small>{{ __('добавлено') }} {{ $comment->created_at->diffForHumans() }}</small></div>
    {{ $comment->content }}
</div>
