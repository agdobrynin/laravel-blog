<x-app-layout pageTitle='New post'>
    <x-post.form
        actionTitle="Update"
        route="{{ route('post.update', [$post]) }}"
        method="PUT"
        :$post
        class="shadow-lg p-4"
    />
</x-app-layout>
