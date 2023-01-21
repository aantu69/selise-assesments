@props(['id', 'error', 'label' => '', 'required' => false])

<div>
    @if ($label != '')
        <label for="{{ $id }}" class="col-form-label" style="font-size:14px;">
            @if ($required)
                <b>{{ $label }} <span style="color: red">*</span></b>
            @else
                <b>{{ $label }}</b>
            @endif
        </label>
    @endif
    {{-- <select {{ $attributes->merge(['class' => 'form-control select']) }} name="{{ $id }}"
        id="{{ $id }}">
        {{ $slot }}
    </select> --}}

    <select {{ $attributes }} class="form-control select @error($error) is-invalid @enderror" name="{{ $id }}"
        id="{{ $id }}">
        {{ $slot }}
    </select>

    {{-- @error($error) is-invalid @enderror

    <input {{ $attributes
        ->class(['default-classes', 'border-red-500' => $errors->has($attributes->get('name'))])
        ->merge(['disabled' => false])
    }} /> --}}

    @error($error)
        <span class="text-danger">{{ $message }}</span><br>
    @enderror
</div>

<div class="form-group row">
    <div class="col-md-12">
        <label for="parent_id" class="col-form-label">Parent Category
            :</label>
        <select class="form-control select" name="parent_id" id="parent_id" wire:model.defer="state.parent_id">
            <option value="">Select Parent Category</option>
            <x-select-categories :categories="$categories" />
        </select>
    </div>
</div>

<div class="row mb-3 {{ $required ? 'required' : '' }}">
    <label for="{{ $id }}" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input {{ $attributes }} type="{{ $type }}" id="{{ $id }}"
            class="form-control @error($error) is-invalid @enderror"
            onchange="this.dispatchEvent(new InputEvent('input'))">

        @if ($hints != '')
            <div class="form-text">{{ $hints }}</div>
        @endif
        @error($error)
            <span class="text-danger">{{ $message }}</span><br>
        @enderror
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#{{ $id }}').on('change', function() {
            @this.set('{{ $attributes->whereStartsWith('wire:model')->first() }}', $(this).val())
        });
    });
</script>
