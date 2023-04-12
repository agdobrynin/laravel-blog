<span>
    {{ __('Автор') }}: {{ $name ?? trans('Аноним') }},

    {{ __('создано :diff', ['diff' => $createdAt->diffForHumans()]) }}

    @if(($updatedAt ?? null) && $updatedAt->diff($createdAt)->invert)
        <span class="badge bg-secondary" title="{{ __('обновлено :diff', ['diff' => $updatedAt->diffForHumans()]) }}">📝</span>
    @endif
</span>
