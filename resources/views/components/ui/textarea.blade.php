@props([
    'name',
    'label',
    'value' => '',
    'cols' => 30,
    'rows' => 10,
    'id' => \Illuminate\Support\Str::uuid(),
])
<div>
    @if($label)
        <label for="{{ $id }}">{{ $label }}</label>
    @endif
    <textarea id="{{ $id }}" name="{{ $name }}" cols="{{ $cols }}" rows="{{ $rows }}">{{ $value }}</textarea>
    @error($name)
        <div>{{ $message }}</div>
    @enderror
</div>
