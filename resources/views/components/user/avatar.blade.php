@props([
    'user',
    'size' => 64
])
@if ($user?->image)
    <img src="{{ $user->image->thumbUrl(128) ? : $user->image->origUrl() }}" alt="{{ __('Аватар пользователя') }}"
         {{ $attributes->merge(['class' => 'avatar avatar-'.$size.' rounded-circle']) }}/>
@else
    <img src="{{  Vite::asset('resources/images/unicorn-icon-svgrepo-com.svg') }}" alt="{{ __('Без аватара') }}"
         {{ $attributes->merge(['class' => 'avatar avatar-'.$size.' rounded-circle bg-secondary']) }}/>
@endif
