<x-app-layout pageTitle="{{ __('Новый пост')  }}">
    <div class="col col-lg-6 mx-auto">
        <x-post.form
            actionTitle="{{ __('Создать') }}"
            route="{{ route('post.store') }}"
            :post="null"
            class="shadow-lg p-4"
        />
    </div>
</x-app-layout>
