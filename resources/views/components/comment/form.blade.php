@props([
    'post'
])
<form action="{{ route('posts.comments.store', ['post' => $post->id]) }}" method="POST" {{ $attributes->merge(['class' => '']) }}>
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
</form>

