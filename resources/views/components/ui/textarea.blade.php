@props([
    'name',
    'label',
    'value' => '',
    'cols' => 30,
    'rows' => 10,
    'id' => \Illuminate\Support\Str::uuid(),
])
<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    @if($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif
    <textarea
        id="{{ $id }}"
        {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}
        name="{{ $name }}"
        cols="{{ $cols }}"
        rows="{{ $rows }}"
    >{{ $value }}</textarea>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
