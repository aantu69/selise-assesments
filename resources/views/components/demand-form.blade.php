<div>
    <h4 class="sub-form-title">{{ __('label.taxpayers_info') }}</h4>
    <div class="form-group row">
        <div class="col-md-6">
            <x-input wire:model.lazy="state.tin" id="tin" error="tin" label="{{ __('label.tin') }}" required />
        </div>
        <div class="col-md-6">
            <x-input wire:model.defer="state.name" id="name" error="name" label="{{ __('label.name') }}"
                placeholder="Name" required />
        </div>
        <div class="col-md-12">
            <x-textarea wire:model.defer="state.address" id="address" error="address"
                label="{{ __('label.address') }}" required />
        </div>
        <div class="col-md-6">
            <x-input wire:model.defer="state.contact_name" id="contact_name" error="contact_name"
                label="{{ __('label.contact_name') }}" required />
        </div>
        <div class="col-md-6">
            <x-input wire:model.defer="state.contact_phone" id="contact_phone" error="contact_phone"
                label="{{ __('label.contact_phone') }}" required />
        </div>
    </div>

    <h4 class="sub-form-title" style="margin: 25px 0 15px;">{{ __('label.demand_info') }}</h4>
    <div class="form-group row">
        <div class="col-md-6">
            <x-select wire:model.defer="state.tax_year" id="tax_year" error="tax_year"
                label="{{ __('label.tax_year') }}" required>
                <option value="">করবর্ষ সিলেক্ট করুন</option>
                @foreach (config()->get('global.previous_tax_year.list') as $key => $key)
                    <option value="{{ $key }}">{{ $key }}</option>
                @endforeach
            </x-select>
        </div>
        <div class="col-md-6">
            <x-input wire:model.defer="state.corresponding_section" id="corresponding_section"
                error="corresponding_section" label="{{ __('label.corresponding_section') }}" required />
        </div>
        <div class="col-md-6">
            <x-datepicker wire:model.defer="state.demand_date" id="demand_date" error="demand_date"
                label="{{ __('label.demand_date') }}" required />
        </div>
        <div class="col-md-6">
            <x-input wire:model.defer="state.demand_amount" id="demand_amount" error="demand_amount"
                label="{{ __('label.demand_amount') }}" type="number" required />
        </div>
    </div>
</div>
