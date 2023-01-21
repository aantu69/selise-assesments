<div>
    <x-flashMessage />
    @if ($viewState)
        <x-datatable :title="$title" :createButton="$createButton" :headers="$headers" :results="$results"
            :sortColumn="$sortColumn" :sortDirection="$sortDirection" :selectPage="$selectPage" :selectAll="$selectAll"
            :checked="$checked" />
    @endif

    @if ($updateState || $createState)
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    @if ($createState) Create @else Update @endif
                    {{ $title }}
                </h3>
            </div>
            <!-- /.card-header -->

            <form autocomplete="off">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <x-input wire:model.defer="state.name" id="name" error="name" label="Name" required />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="permission" class="col-form-label">
                                <b>Permissions <span style="color: red">*</span> :</b>
                            </label>
                            <div>
                                @foreach ($permissions as $permission)
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="permission_{{ $permission->id }}"
                                            name="permission_{{ $permission->id }}" value="{{ $permission->id }}"
                                            wire:model.lazy='selectedPermissions'>
                                        <label
                                            for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>&nbsp;&nbsp;
                                @endforeach
                            </div>
                            @error('selectedPermissions') <span
                                class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <x-card-footer-button :createState="$createState" :updateState="$updateState" />
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

        });


        // document.addEventListener("livewire:load", function(event) {
        //     Livewire.hook('message.processed', (component) => {
        //         //$('.select2').select2();
        //         $('input[type="checkbox"].square-red, input[type="radio"].square-red').iCheck({
        //             checkboxClass: 'icheckbox_square-red',
        //             radioClass   : 'iradio_square-red'
        //         });
        //         $('.permission').on('ifChecked', function(e){
        //             @this.selectedPermissions.push($(this).val());
        //             alert(@this.selectedPermissions);
        //         });

        //         $('.permission').on('ifUnchecked', function(e){
        //             var index = (@this.selectedPermissions).indexOf(parseInt($(this).val()));
        //             if (index > -1) {
        //                 @this.selectedPermissions.splice(index, 1);
        //             }
        //             alert(@this.selectedPermissions);
        //         });
        //     });
        // });
    </script>

@endpush
