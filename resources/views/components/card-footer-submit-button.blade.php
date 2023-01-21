@props(['createState' => true, 'updateState' => false, 'nextState' => false])

<div>
    <button class="btn btn-sm btn-info" wire:click.prevent="cancel">{{ __('label.Back') }}</button>
    @if ($createState)
        <x-loading-button type="submit" id="save" class="btn-success">{{ __('label.Save') }}
        </x-loading-button>
    @endif
    @if ($updateState)
        <x-loading-button type="submit" id="save" class="btn-success">{{ __('label.Update') }}
        </x-loading-button>
    @endif
</div>
