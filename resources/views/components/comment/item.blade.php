@props(['comment'])
<div class="border rounded shadow-sm p-3 my-3">
    <div class="fw-lighter"><small>Added {{ $comment->created_at->diffForHumans() }}</small></div>
    {{ $comment->content }}
</div>
