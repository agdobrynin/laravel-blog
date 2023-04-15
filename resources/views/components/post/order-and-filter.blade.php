@props([
    'blogPostFilterDto',
    'tags' => collect(),
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
    @if($tags->count())
        <div class="col-auto">
            <label for="orderBy" class="">{{ __('Тег поста') }}</label>
            <select class="form-select-sm" name="tag">
                <option value="">{{ __('любой') }}</option>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}"
                            @if($tag->id === $blogPostFilterDto->tag?->id) selected @endif>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary btn-sm mb-3">{{ __('применить') }}</button>
    </div>
</form>
