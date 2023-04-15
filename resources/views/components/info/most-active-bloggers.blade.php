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
            <li class="list-group-item">
                <span class="fw-bold">{{ $blogger->name }}</span>
                , опубликовал <span class="badge bg-info">{{ $blogger->blog_posts_count }}</span> постов
            </li>
        @endforeach
    </x-slot:items>
</x-ui.card>
