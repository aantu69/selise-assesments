@props(['id', 'error', 'label' => '', 'hints' => '', 'required' => false, 'type' => 'text'])

{{-- <div>
    @if ($label != '')
        <label for="{{ $id }}" class="col-form-label" style="font-size:14px;">
            @if ($required)
                <b>{{ $label }} <span style="color: red">*</span></b>
            @else
                <b>{{ $label }} :</b>
            @endif
        </label>
    @endif
    <textarea {{ $attributes }} id="{{ $id }}" onClick="this.select();"
        class="form-control @error($error) is-invalid @enderror" onchange="this.dispatchEvent(new InputEvent('input'))"></textarea>
    @error($error)
        <span class="text-danger">{{ $message }}</span><br>
    @enderror
</div> --}}

<div class="row mb-3 {{ $required ? 'required' : '' }}">
    <label for="{{ $id }}" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <textarea {{ $attributes }} id="{{ $id }}" class="form-control @error($error) is-invalid @enderror"
            onchange="this.dispatchEvent(new InputEvent('input'))"></textarea>
        @if ($hints != '')
            <div class="form-text">{{ $hints }}</div>
        @endif
        @error($error)
            <span class="text-danger">{{ $message }}</span><br>
        @enderror
    </div>
</div>
