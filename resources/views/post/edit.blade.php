<x-app-layout pageTitle="{{ __('Обновить пост') }}">
    <x-post.form
        actionTitle="{{ __('Обновить') }}"
        route="{{ route('post.update', [$post]) }}"
        method="PUT"
        :$post
        class="shadow-lg p-4"
    />
</x-app-layout>
