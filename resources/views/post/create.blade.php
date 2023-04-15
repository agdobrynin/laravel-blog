<x-app-layout pageTitle="{{ __('Новый пост')  }}">
    <x-post.form
        actionTitle="{{ __('Создать') }}"
        route="{{ route('post.store') }}"
        :post="null"
        class="shadow-lg p-4"
    />
</x-app-layout>
