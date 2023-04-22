<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #e7e7e7;
        color: #2d2d2d;
    }
    blockquote {
        border-left: 2px solid maroon;
        padding: 1em 1em 1em 2em;
        margin-left: 1em;
        box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.2);
        background-color: white;
    }

    .avatar {
        vertical-align: middle;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin: 0em 1em 1em 0em;
        float: left;
        border: 1px solid gray;
        box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.2);
    }

    .clear {
        clear: both;
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
    @if($comment->user?->image)
        <img class="avatar"
             alt="{{ __('Аватар пользователя') }}"
             src="{{ $message->embed($comment->user->image->fullPath()) }}"/>
    @else
        <img class="avatar"
             alt="{{ __('Аватар пользователя') }}"
             src="{{ $message->embed(resource_path('/images/unicorn-icon-svgrepo-com.svg')) }}"/>
    @endif

    {{ $comment->content }}
    <div class="clear"></div>
</blockquote>
