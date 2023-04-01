@props([
    'name',
    'label',
    'value' => '',
    'type' => 'text',
    'id' => \Illuminate\Support\Str::uuid(),
])
<div {{ $attributes->merge(['class' => 'input-group']) }}>
    @if($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif
    <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{{ $value }}">
    @error($name)
        <div>{{ $message }}</div>
    @enderror
</div>
