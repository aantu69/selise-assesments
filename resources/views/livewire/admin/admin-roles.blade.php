<div>
    <x-flashMessage />
    @if ($viewState)
        <x-datatable :title="$title" :createButton="$createButton" :headers="$headers" :results="$results" :sortColumn="$sortColumn"
            :sortDirection="$sortDirection" :selectPage="$selectPage" :selectAll="$selectAll" :checked="$checked" />
    @endif

    @if ($updateState || $createState)
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    @if ($createState)
                        Create
                    @else
                        Update
                    @endif
                    {{ $title }}
                </h3>
            </div>
            <!-- /.card-header -->

            <form autocomplete="off">
                <div class="card-body">
                    <x-input wire:model.defer="state.name" id="name" error="name" label="Name" required />
                    <x-checkbox-list wire:model.lazy="selectedPermissions" error="selectedPermissions"
                        label="Permissions" :options="$permissions" required />
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
