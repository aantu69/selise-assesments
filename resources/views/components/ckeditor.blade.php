@props(['id', 'error', 'label' => '', 'hints' => '', 'required' => false, 'type' => 'text'])

<div wire:ignore class="row mb-3 {{ $required ? 'required' : '' }}">
    <label for="{{ $id }}" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <textarea {{ $attributes }} id="{{ $id }}" class="form-control @error($error) is-invalid @enderror">{{ $slot }}</textarea>
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
        CKEDITOR.replace('{{ $id }}');
        document.querySelector('#save').addEventListener('click', () => {
            let ckEditorVal = CKEDITOR.instances.{{ $id }}.getData();
            @this.set("{{ $attributes->whereStartsWith('wire:model')->first() }}", ckEditorVal);
        });
    });
</script>
