<x-app-layout pageTitle='New post'>
    <x-post.form
        actionTitle="Add"
        route="{{ route('post.store') }}"
        :post="null"
    />
</x-app-layout>
