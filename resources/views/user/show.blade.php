<x-app-layout pageTitle="{{ __('Пользователь сайта') }}">
    <div class="row align-items-center">
        <div class="col-12 col-md-4 text-md-end text-center">
            <x-user.avatar :$user :size="128"/>
        </div>
        <div class="col-12 col-md-8">
            <x-ui.input :disabled="true" name="" value="{{ $user->name }}" label="{{ __('Имя пользователя') }}"/>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary w-100">{{ __('Изменить имя или аватар') }}</a>
        </div>
    </div>
</x-app-layout>
