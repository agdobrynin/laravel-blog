<div {{ $attributes->merge(['class' => 'alert alert-'.$type]) }}>
    <div>
        {{ $slot }}
    </div>
</div>
