@props(['comment'])
<div class="border rounded shadow-sm p-3 my-3">
    @if($comment->trashed()) <del class="text-muted"> @endif
    <div class="fw-lighter">
        <small>
            <x-ui.author-and-date
                :name="$comment['user']['name'] ?? null"
                :created_at="$comment['created_at']"
            />
        </small>
    </div>
    <div style="white-space: pre-wrap;">{{ $comment->content }}</div>
    @if($comment->trashed()) <del class="text-muted"> @endif
</div>
