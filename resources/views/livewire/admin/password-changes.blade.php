<div>
    <x-flashMessage />

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                Change Password
            </h3>
        </div>
        <!-- /.card-header -->

        <form>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <x-input wire:model.defer="current_password" id="current_password" error="current_password"
                            label="Current Password" type="password" required />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <x-input wire:model.defer="password" id="password" error="password" label="New Password"
                            type="password" required />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <x-input wire:model.defer="password_confirmation" id="password_confirmation"
                            error="password_confirmation" label="Confirm Password" type="password" required />
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <x-loading-button wire:click.prevent="changePassword" class="btn-success">Update</x-loading-button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
