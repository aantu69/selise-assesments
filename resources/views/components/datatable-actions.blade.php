{{-- @if (isset($editAction))
    <button wire:click="edit({{ $value }})" class="btn btn-primary btn-sm">Edit</button>
@endif

@if (isset($deletAction))
    <button wire:click="confirmDelete({{ $value }})" class="btn btn-danger btn-sm">Delete</button>
@endif --}}

<div class="dropdown">
    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-ellipsis-h text-muted"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
        @if (isset($editAction) && $editAction)
            <a wire:click="edit('{{ $value }}')" class="dropdown-item btnprnt">{{ __('label.Edit') }}</a>
            <div class="dropdown-divider"></div>
        @endif
        @if (isset($showAction) && $showAction)
            <a wire:click="show({{ $value }})" class="dropdown-item">{{ __('label.Show') }}</a>
            <div class="dropdown-divider"></div>
        @endif
        @if (isset($deletAction) && $deletAction)
            <a wire:click="confirmDelete({{ $value }})" class="dropdown-item">{{ __('label.Delete') }}</a>
        @endif
    </div>
</div>

{{-- <div class="btn-group">
    <a wire:click="edit('{{ $value }}')" data-bs-toggle="tooltip" title="" class="btn btn-primary"
        data-bs-original-title="{{ __('label.Edit') }}" aria-label="{{ __('label.Edit') }}">
        <i class="fa-solid fa-pencil"></i>
    </a>
    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"
        aria-expanded="false"></button>
    <div class="dropdown-menu dropdown-menu-end" style="">
        @if (isset($showAction) && $showAction)
            <a wire:click="show({{ $value }})" class="dropdown-item">
                <i class="fa-solid fa-plus"></i>{{ __('label.Show') }}</a>
        @endif
        @if (isset($deletAction) && $deletAction)
            <a wire:click="confirmDelete({{ $value }})" class="dropdown-item">
                <i class="fa-solid fa-plus"></i>{{ __('label.Delete') }}
            </a>
        @endif
    </div>
</div> --}}

{{-- <div class="btn-group">
    <button type="button" wire:click="edit('{{ $value }}')" class="btn btn-info">{{ __('label.Edit') }}</button>
    <button type="button" class="btn btn-info dropdown-toggle px-3" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
    </button>
    <div class="dropdown-menu">
        @if (isset($showAction) && $showAction)
            <a class="dropdown-item" wire:click="show('{{ $value }}')">{{ __('label.Show') }}</a>
            <div class="dropdown-divider"></div>
        @endif
        @if (isset($deletAction) && $deletAction)
            <a class="dropdown-item" wire:click="confirmDelete('{{ $value }}')">{{ __('label.Delete') }}</a>
        @endif
    </div>
</div> --}}

{{-- <div class="btn-group">
    <button type="button" class="btn btn-primary">Primary</button>
    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
        <span class="visually-hidden">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu">
        <a href="#" class="dropdown-item">Action</a>
        <a href="#" class="dropdown-item">Another action</a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">Separated link</a>
    </div>
</div> --}}
