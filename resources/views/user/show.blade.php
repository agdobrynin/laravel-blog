<x-app-layout pageTitle="{{ __('Пользователь сайта') }}">
    <div class="row align-items-center">
        <div class="col-12 col-md-4 text-md-end text-center">
            <x-user.avatar :$user :size="128"/>
        </div>
        <div class="col-12 col-md-8">
            <x-ui.input :disabled="true" name="" value="{{ $user->name }}" label="{{ __('Имя пользователя') }}"/>
            @can('update', $user)
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary w-100">{{ __('Изменить имя или аватар') }}</a>
            @endcan
        </div>
    </div>

    <x-comment.list-with-form
        :$comments
        title="{{ __('Добавить комментарий для пользователя :name', ['name' => $user->name]) }}"
        action="{{ route('users.comments.store', $user) }}"/>
</x-app-layout>
