@props([
    'user',
    'createdAt' => null,
    'updatedAt' => null,
    'avatarSize' => 24
])
<span>
    <x-user.avatar :$user :size="$avatarSize" {{ $attributes->merge(['class' => '']) }}/>
    {{ __('Автор') }}:
    @if($user)
        <a href="{{ route('users.show', $user) }}">{{$user->name}}</a>
    @else
        {{ trans('Аноним') }}
    @endif

    {{ __('создано :diff', ['diff' => $createdAt->diffForHumans()]) }}

    @if(($updatedAt ?? null) && $updatedAt->diff($createdAt)->invert)
        <span class="badge bg-secondary" title="{{ __('обновлено :diff', ['diff' => $updatedAt->diffForHumans()]) }}">📝</span>
    @endif
</span>
