@props([
    'name',
    'label',
    'value' => '',
    'hasError' => false,
    'type' => 'text',
    'id' => \Illuminate\Support\Str::uuid(),
])

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    @if($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif
    <input id="{{ $id }}"
           {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}
           type="{{ $type }}"
           name="{{ $name }}"
           value="{{ $value }}">
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
