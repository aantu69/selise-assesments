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
                    <div class="form-group row">
                        <div class="col-md-6">
                            <x-input wire:model.defer="state.name" id="name" error="name" label="Name" required />
                        </div>
                        <div class="col-md-6">
                            <x-input wire:model.defer="state.email" id="email" error="email" label="Email" type="email"
                                required />
                        </div>
                        <div class="col-md-6">
                            <x-input wire:model.defer="state.password" id="password" error="password" label="Password"
                                type="password" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="role"><b>Roles <span style="color: red">*</span> :</b></label>
                            <div>
                                @foreach ($roles as $role)
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="role_{{ $role->id }}"
                                            name="role_{{ $role->id }}" value="{{ $role->id }}"
                                            wire:model.lazy='selectedRoles'>
                                        <label for="role_{{ $role->id }}">{{ $role->name }}</label>
                                    </div>&nbsp;&nbsp;
                                @endforeach
                            </div>
                            @error('selectedRoles')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="circle"><b>Circles <span style="color: red">*</span> :</b></label>
                            <div>
                                @foreach ($circles as $circle)
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="circle_{{ $circle->id }}"
                                            name="circle_{{ $circle->id }}" value="{{ $circle->id }}"
                                            wire:model.lazy='selectedCircles'>
                                        <label for="circle_{{ $circle->id }}">{{ $circle->name }}</label>
                                    </div>&nbsp;&nbsp;
                                @endforeach
                            </div>
                            @error('selectedRoles')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
        //         $(".alert").slideDown(300).delay(5000).slideUp(300);
        //     });
        // });
    </script>
@endpush
