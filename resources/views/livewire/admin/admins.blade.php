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

            <form>
                <div class="card-body">
                    <x-input wire:model.defer="state.name" id="name" error="name" label="Name" required />
                    <x-input wire:model.defer="state.email" id="email" error="email" label="Email" type="email"
                        required />
                    <x-input wire:model.defer="state.password" id="password" error="password" label="Password"
                        type="password" required />

                    <x-checkbox-list wire:model.lazy="selectedRoles" error="selectedRoles" label="Roles"
                        :options="$roles" required />
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
        //         $(".alert").slideDown(300).delay(5000).slideUp(300);
        //     });
        // });
    </script>
@endpush
