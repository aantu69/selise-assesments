<div class="col-md-6">
    <x-select wire:model.defer="state.action_type_id" id="action_type_id" error="action_type_id"
        label="{{ __('label.action_type_id') }}" required>
        <option value="">গৃহীত কার্যক্রম সিলেক্ট করুন</option>
        @foreach ($action_types as $action_type)
            <option value="{{ $action_type->id }}">{{ $action_type->name }}</option>
        @endforeach
    </x-select>
</div>
