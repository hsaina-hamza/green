@props(['method' => 'POST', 'action' => null, 'hasFiles' => false])

<form 
    method="{{ in_array($method, ['GET', 'POST']) ? $method : 'POST' }}"
    action="{{ $action }}"
    {!! $hasFiles ? 'enctype="multipart/form-data"' : '' !!}
    {{ $attributes->merge(['class' => $formClasses]) }}
>
    @csrf
    @if(!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif

    <div class="space-y-6">
        {{ $slot }}
    </div>

    @if(isset($actions))
        <div class="mt-6 flex items-center justify-end space-x-3">
            {{ $actions }}
        </div>
    @endif
</form>
