@props([
    'user',
    'size' => 64
])
@if ($user?->image)
    <img src="{{ $user->image->url() }}" alt="{{ __('Аватар пользователя') }}"
         class="avatar avatar-{{$size}} rounded-circle shadow-4">
@else
    <img src="{{  Vite::asset('resources/images/unicorn-icon-svgrepo-com.svg') }}" alt="{{ __('Без аватара') }}"
         class="avatar avatar-{{$size}} rounded-circle shadow-4 bg-secondary">
@endif
