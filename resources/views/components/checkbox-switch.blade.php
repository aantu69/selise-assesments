@props(['id', 'label' => '', 'hints' => '', 'type' => 'checkbox'])

<div class="row mb-3">
    <label for="{{ $id }}" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <div class="form-check form-switch form-switch-lg">
            <input {{ $attributes }} type="{{ $type }}" id="{{ $id }}" name="{{ $id }}"
                class="form-check-input">
        </div>
        @if ($hints != '')
            <div class="form-text">{{ $hints }}</div>
        @endif

    </div>
</div>
