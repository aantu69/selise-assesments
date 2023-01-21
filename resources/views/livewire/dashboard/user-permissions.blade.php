<div>
    <x-flashMessage />
    @if ($viewState)
        <x-datatable :title="$title" :createButton="$createButton" :headers="$headers" :results="$results"
            :sortColumn="$sortColumn" :sortDirection="$sortDirection" :selectPage="$selectPage" :selectAll="$selectAll"
            :checked="$checked" />
    @endif

    @if ($createState || $updateState)
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
                    <div class="form-group">
                        <div class="col-md-6">
                            <x-input wire:model.defer="state.name" id="name" error="name" label="Name" required />
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
    </script>
@endpush
