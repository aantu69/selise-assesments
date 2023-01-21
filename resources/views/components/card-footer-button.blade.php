@props(['createState' => true, 'updateState' => false, 'nextState' => false])

<div>
    {{-- <x-loading-button wire:click.prevent="cancel" class="btn-info">Back</x-loading-button> --}}
    <button class="btn btn-sm btn-info" wire:click.prevent="cancel">{{ __('label.Back') }}</button>
    @if ($createState)
        <x-loading-button wire:click.prevent="store" id="save" class="btn-success">{{ __('label.Save') }}
        </x-loading-button>
    @endif
    @if ($updateState)
        <x-loading-button wire:click.prevent="update" id="save" class="btn-success">{{ __('label.Update') }}
        </x-loading-button>
    @endif
</div>
