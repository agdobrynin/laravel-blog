@props([
    'blogPostFilterDto'
])
<form {{ $attributes->merge(['class' => 'row g-3']) }}>
    <div class="col-auto fw-light">{{ __('Фильтры') }}</div>
    <div class="col-auto">
        <label for="orderBy" class="visually-hidden">{{ __('Показать посты по') }}</label>
        <select class="form-select-sm" name="order">
            @foreach(App\Enums\OrderBlogPostEnum::cases() as $enum)
                <option value="{{ Str::lower($enum->name) }}"
                    @if($enum === $blogPostFilterDto->order) selected @endif>{{ $enum->value }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary btn-sm mb-3">{{ __('применить') }}</button>
    </div>
</form>
