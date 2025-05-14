@props([
    'method' => 'POST',
    'action' => null,
    'hasFiles' => false,
    'formClasses' => 'space-y-6',
    'actionsAlignment' => 'end' // start/center/end
])

<form 
    method="{{ in_array($method, ['GET', 'POST']) ? $method : 'POST' }}"
    action="{{ $action }}"
    {!! $hasFiles ? 'enctype="multipart/form-data"' : '' !!}
    {{ $attributes->merge(['class' => $formClasses]) }}
    dir="rtl"
>
    @csrf
    @if(!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif

    <div class="space-y-6">
        {{ $slot }}
    </div>

    @if(isset($actions))
        <div @class([
            'mt-6 flex items-center gap-3',
            'justify-start' => $actionsAlignment === 'start',
            'justify-center' => $actionsAlignment === 'center',
            'justify-end' => $actionsAlignment === 'end',
            'flex-row-reverse' => $actionsAlignment !== 'center' // Reverse button order for RTL
        ])>
            {{ $actions }}
        </div>
    @endif
</form>