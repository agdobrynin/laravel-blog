@props([
    'user',
    'size' => 64,
    'imgWith' => 128,
])
@inject('storage', \App\Services\Contracts\AvatarImageStorageInterface::class)
@if ($user?->image)
    <img src="{{ $storage->thumbUrl($user->image->path, $imgWith) }}" alt="{{ __('Аватар пользователя') }}"
         {{ $attributes->merge(['class' => 'avatar avatar-'.$size.' rounded-circle']) }}/>
@else
    <img src="{{  Vite::asset('resources/images/unicorn-icon-svgrepo-com.svg') }}" alt="{{ __('Без аватара') }}"
         {{ $attributes->merge(['class' => 'avatar avatar-'.$size.' rounded-circle bg-secondary']) }}/>
@endif
