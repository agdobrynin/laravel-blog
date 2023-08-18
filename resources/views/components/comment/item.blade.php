@props(['comment'])
<div class="border rounded shadow-sm p-3 my-3">
    @if($comment->trashed()) <del class="text-muted"> @endif
    <div class="fw-lighter">
        <small>
            <x-user.author-and-date
                :user="$comment['user']"
                :created_at="$comment['created_at']"
                avatarSize="24"
                avatarImgSize="24"
            />
        </small>
    </div>
    <div style="white-space: pre-wrap;">{{ $comment->contentWithoutTags() }}</div>
        @if($comment->tags_count)
            <div class="text-lowercase pt-3 fw-lighter">
                {{ __('тэги комментария:') }}
                <x-info.tags :tags="$comment['tags']" class="bg-secondary fw-lighter text-light"/>
            </div>
        @endif
    @if($comment->trashed()) </del> @endif
</div>
