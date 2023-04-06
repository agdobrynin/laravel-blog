@props(['comment'])
<div class="border rounded shadow-sm p-3 my-3">
    <div class="fw-lighter">
        <small>
            {{ __('добавлено') }} {{ $comment->created_at->diffForHumans() }}
            {{ __('пользователем') }} {{ $comment->user->name ?? trans('Аноним') }}
        </small>
    </div>
    {{ $comment->content }}
</div>
