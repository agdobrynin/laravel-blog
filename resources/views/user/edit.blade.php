<x-app-layout pageTitle="{{ __('Изменение пользователя сайта') }}">
    <form action="{{ route('users.update', $user) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row align-items-center">
            <div class="col-12 col-md-4 text-md-end text-center">
                <x-user.avatar :$user :size="128" />
            </div>
            <div class="col-12 col-md-8">
                <x-ui.input name="name"
                            label="{{ __('Имя пользователя') }}"
                            class="input-small"
                            value="{{ old('name', $user->name ?? '') }}"
                />
                <x-ui.input name="avatar"
                            type="file"
                            label="{{ __('Аватар пользователя') }}"
                            class="input-small"/>
                <button class="btn btn-primary w-100" type="submit">{{ __('Обновить') }}</button>
            </div>
        </div>
    </form>
</x-app-layout>
