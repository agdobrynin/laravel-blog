<x-app-layout pageTitle="{{ __('Пользователь сайта') }}">
    <div class="row align-items-center">
        <div class="col-12 col-md-4 text-md-end text-center">
            <x-user.avatar :$user :size="128"/>
        </div>
        <div class="col-12 col-md-8">
            <x-ui.input :disabled="true" name="" value="{{ $user->name }}" label="{{ __('Имя пользователя') }}"/>
            @can('update', $user)
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary w-100">{{ __('Изменить профиль') }}</a>
            @endcan
            @if(!empty($readNowCount))
                <div class="text-muted fw-lighter mt-2">
                    {{ trans_choice('{1} Сейчас читает :count пользователь|[2,19] Сейчас читает :count пользователей', $readNowCount) }}
                </div>
            @endif
        </div>
    </div>

    <h4 class="mt-4 text-secondary">{{ __('Добавить комментарий для пользователя :name', ['name' => $user->name]) }}</h4>
    @if(Auth::user())
        <x-comment.form
            action="{{ route('users.comments.store', $user) }}"
            class="border rounded p-3 shadow-sm"/>
    @else
        <div class="border rounded shadow-sm p-3 my-3">
            {!! __('Для комментария <a href=":login">авторизуйтесь</a> или <a href=":reg">зарегистрируйтесь</a>',
                        ['login' => route('login'), 'reg' => route('register')]) !!}
        </div>
    @endif
    <x-comment.list-with-pagination :$comments/>
</x-app-layout>
