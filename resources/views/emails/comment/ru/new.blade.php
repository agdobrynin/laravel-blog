<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #e7e7e7;
        color: #2d2d2d;
    }
    blockquote {
        border-left: 2px solid maroon;
        padding: 1em 0 1em 2em;
        margin-left: 1em;
    }

    .avatar {
        vertical-align: middle;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 1em;
        float: left;
    }
</style>
<p>Привет {{ $comment->commentable->user->name }}</p>
<p>Новый комментарий на Ваш пост &laquo;<a
        href="{{ route('posts.show', [$comment->commentable]) }}">{{ $comment->commentable->title }}</a>&raquo;</p>
<p>Комментатор
    @if($comment->user)
        <a href="{{ route('users.show', [$comment->user]) }}">{{ $comment->user->name }}</a>
    @else
        {{ trans('Аноним') }}
    @endif
    написал:</p>
<blockquote>
    {{ $comment->content }}
</blockquote>
