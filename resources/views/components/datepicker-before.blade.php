@props(['id', 'error', 'hints' => '', 'label' => '', 'type' => 'text', 'required' => false])

{{-- <div>
    @if ($label != '')
        <label for="{{ $id }}" class="col-form-label" style="font-size:14px;">
            @if ($required)
                {{ $label }} <span style="color: red">*</span>
            @else
                {{ $label }}
            @endif
        </label>
    @endif
    <input {{ $attributes }} class="form-control @error($error) is-invalid @enderror" type="{{ $type }}"
        id="{{ $id }}" onClick="this.select();" readonly>
    @error($error)
        <span class="text-danger">{{ $message }}</span><br>
    @enderror
    @if ($format == 'true')
        <span style="color: green">৩১/০১/১৯৭১ (দিন/মাস/বছর)</span>
    @endif
</div> --}}

<div class="row mb-3 {{ $required ? 'required' : '' }}">
    <label for="{{ $id }}" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <input {{ $attributes }} type="{{ $type }}" id="{{ $id }}"
            class="form-control @error($error) is-invalid @enderror" onClick="this.select();">
        @if ($hints != '')
            <div class="form-text">{{ $hints }}</div>
        @endif
        @error($error)
            <span class="text-danger">{{ $message }}</span><br>
        @enderror
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#{{ $id }}').datepicker({
                dateFormat: '{{ config('app.date_format_js') }}',
                changeMonth: true,
                changeYear: true,
                yearRange: "-60:+0",
                showMonthAfterYear: true,
                autoSize: true,
                onSelect: function(selectedDate) {
                    @this.set("{{ $attributes->whereStartsWith('wire:model')->first() }}",
                        selectedDate);
                }
            });
        });
    </script>
@endpush
