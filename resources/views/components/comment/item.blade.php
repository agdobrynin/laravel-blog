@props(['comment'])
<div class="border rounded shadow-sm p-3 my-3">
    <div class="fw-lighter">
        <small>
            <x-ui.author-and-date
                :name="$comment['user']['name'] ?? null"
                :created_at="$comment['created_at']"
            />
        </small>
    </div>
    {{ $comment->content }}
</div>
