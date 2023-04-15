@props([
    'name',
    'label',
    'values' => [],
    'data' => [],
    'hasError' => false,
    'multiple' => false,
    'id' => \Illuminate\Support\Str::uuid(),
])

<div {{ $attributes->merge(['class' => 'mb-3']) }}>
    @if($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif
    <select
        name="{{ $name }}@if($multiple)[]@endif"
        @if($multiple) multiple @endif
        id="{{ $id }}"
        {{ $attributes->class(['form-select', 'is-invalid' => $errors->has($name)]) }}
    >
        @foreach($values as $value => $text)
            <option value="{{ $value }}" @if(in_array($value, $data)) selected @endif>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
