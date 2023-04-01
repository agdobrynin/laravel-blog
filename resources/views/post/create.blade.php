<x-app-layout pageTitle='New post'>
    <div class="col col-lg-6 mx-auto">
        <x-post.form
            actionTitle="Add"
            route="{{ route('post.store') }}"
            :post="null"
            class="shadow-lg p-4"
        />
    </div>
</x-app-layout>
