Привет {{ $comment->commentable->user->name }}.
Новый комментарий на Ваш пост "{{ $comment->commentable->title }}"
[ ссылка: {{ route('posts.show', [$comment->commentable]) }} ]

Комментатор {{ $comment->user?->name ?? trans('Аноним') }} написал:
-----------------
{{ $comment->content }}
-----------------
@if($comment->user)
[ ссылка на профиль комментатора: {{ route('users.show', [$comment->user]) }} ]
@endif
