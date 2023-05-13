@props([
    'fullOrigPath'
])
<img class="avatar"
    alt="User avatar"
    src="file://{{ $fullOrigPath ? : resource_path('/images/unicorn-icon-svgrepo-com.svg') }}"/>
