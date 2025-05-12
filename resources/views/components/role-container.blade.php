@props(['type' => 'both', 'padded' => true, 'rounded' => true, 'shadowed' => true])

<div {{ $attributes->merge(['class' => $containerClasses]) }}>
    @if(isset($header))
        <div class="mb-4">
            {{ $header }}
        </div>
    @endif

    {{ $slot }}

    @if(isset($footer))
        <div class="mt-4">
            {{ $footer }}
        </div>
    @endif
</div>
