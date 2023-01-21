@props(['id', 'error', 'label' => '', 'hints' => '', 'required' => false, 'type' => 'checkbox', 'options'])

<div class="row mb-3 {{ $required ? 'required' : '' }}">
    <label class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <div class="row" style="margin-top: 10px;">
            @foreach ($options as $option)
                <div class="col-md-3 icheck-primary d-inline">
                    <input {{ $attributes }} type="{{ $type }}" id="role_{{ $option->id }}"
                        name="role_{{ $option->id }}" value="{{ $option->id }}"
                        onchange="this.dispatchEvent(new InputEvent('input'))">
                    <label for="role_{{ $option->id }}">{{ $option->name }}</label>
                </div>
            @endforeach
        </div>
        @if ($hints != '')
            <div class="form-text">{{ $hints }}</div>
        @endif
        @error($error)
            <span class="text-danger">{{ $message }}</span><br>
        @enderror
    </div>
</div>
