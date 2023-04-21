@props([
    'mostActiveBloggerDto'
])
<x-ui.card title="{{ trans('Самые активные блогеры') }}" class="border-info">
    <x-slot:subtitle>
        @if($mostActiveBloggerDto->minCountPost)
            {{ __('опубликовавшие :count постов и более', ['count' => $mostActiveBloggerDto->minCountPost]) }}
        @endif
        @if($mostActiveBloggerDto->lastMonth)
            {{ __('за последние :month месяцев', ['month' => $mostActiveBloggerDto->lastMonth]) }}
        @endif
    </x-slot:subtitle>
    <x-slot:items>
        @foreach($mostActiveBloggerDto->bloggers as $blogger)
            <a href="{{ route('users.show', $blogger) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <h6 class="mb-1">{{ $blogger->name }}</h6>
                    <div class="text-muted me-2"><x-user.avatar :user="$blogger" size="48"/></div>
                </div>
                <p class="mb-1">опубликовал <span class="badge bg-info">{{ $blogger->blog_posts_count }}</span> постов</p>
            </a>
        @endforeach
    </x-slot:items>
</x-ui.card>
