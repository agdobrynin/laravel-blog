@props([
    'action'
])
<form action="{{ $action }}" method="POST" {{ $attributes->merge(['class' => '']) }}>
    @csrf
    <x-ui.input label="{{ __('От имени') }}"
                name=""
                disabled="true"
                class="form-control-sm"
                value="{{ Auth::user()?->name ?? trans('Аноним') }}"/>
    <x-ui.textarea
        class="form-control-sm"
        name="content"
        label="{{ __('Ваш комментарий') }}"
        rows="2"
        value="{{ old('content', $comment->content ?? '') }}"/>
    <input type="submit" class="btn btn-primary btn-sm w-100" value="{{ __('Добавить') }}">

    @inject('tagsDictionary', App\Services\Contracts\TagsDictionaryInterface::class)

    @if($tagsDictionary->tags()->count())
        <p class="text-muted mt-4">
            {{ __('Можно использовать теги на комментарии указав значение тэга между символов решётка "#"') }}
        </p>
        <div class="text-muted fs-small">{{ __('Список доступных тэгов:') }}</div>
        <div class="row">
            @foreach($tagsDictionary->tags() as $tag)
                <div class="col text-muted text-nowrap fs-small">#{{ $tag->name }}#</div>
            @endforeach
        </div>

    @endif
</form>

