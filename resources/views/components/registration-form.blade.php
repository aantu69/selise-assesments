<div>
    <div class="form-group row">
        <div class="col-md-12">
            <x-input wire:model.defer="state.first_name" id="first_name" error="first_name" label="First Name" required />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <x-input wire:model.defer="state.last_name" id="last_name" error="last_name" label="Last Name" required />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <x-input wire:model.defer="state.email" id="email" error="email" label="Email" type="email"
                required />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <x-input wire:model.defer="state.password" id="password" error="password" label="Password" type="password"
                required />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <x-input wire:model.defer="state.password_confirmation" id="password_confirmation"
                error="password_confirmation" label="Confirm Password" type="password" required />
        </div>
    </div>
</div>
