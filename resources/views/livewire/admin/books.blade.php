<div>
    <x-flashMessage />
    {{-- <x-categories :categories="$categories" /> --}}
    @if ($viewState)
        <x-datatable :title="$title" :createButton="$createButton" :headers="$headers" :results="$results" :sortColumn="$sortColumn"
            :sortDirection="$sortDirection" :selectPage="$selectPage" :selectAll="$selectAll" :checked="$checked" />
    @endif

    @if ($updateState || $createState)
        <div class="card">
            <div class="card-header">
                <i class="fas fa-pencil-alt"></i>
                @if ($createState)
                    Create
                @else
                    Update
                @endif
                {{ $title }}
            </div>
            <div class="card-body">
                <x-input wire:model.defer="state.name" id="name" error="name" label="Book Name" required />
                <x-input wire:model.defer="state.author" id="author" error="author" label="Author Name" required />
                <x-input wire:model.defer="state.price" id="price" error="price" label="Price" type="number"
                    required />

                <div class="row mb-3 required">
                    <label for="photo" class="col-sm-2 col-form-label">Picture</label>
                    <div class="col-sm-10">
                        <div class="form-group row">
                            <div class="col-md-12 profile_pic">
                                <div class="cropit-overlay"
                                    style="margin-top:10px;width:100px;height:100px;position:absolute;z-index:9999;">
                                </div>
                                <div class="cropit-preview" id="imagePreview">
                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Profile Picture"
                                            style="width: 100%">
                                    @else
                                        @if ($createState)
                                            <img src="{{ asset('images/pp.png') }}" alt="Profile Picture"
                                                style="width: 100%">
                                        @else
                                            <img src="{{ $state['photo_url'] }}" alt="Profile Picture"
                                                style="width: 100%">
                                        @endif
                                    @endif
                                </div>
                                @error('photo')
                                    <div class="profile-pic-info">
                                        <p class="upload_note text-danger">{{ $message }}</p>
                                    </div>
                                @enderror
                                <span class="text-info"><b>Valid file types: jpeg, jpg & png only. Max File Size:
                                        100KB</b></span><br>
                                <span class="btn fileinput-button">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <div wire:loading wire:target="photo" style="margin: 0">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            style="margin: 0; background: none; display: inline; shape-rendering: auto;"
                                            width="15px" height="15px" viewBox="0 0 100 100"
                                            preserveAspectRatio="xMidYMid">
                                            <path d="M10 50A40 40 0 0 0 90 50A40 44 0 0 1 10 50" fill="#ffffff"
                                                stroke="none">
                                                <animateTransform attributeName="transform" type="rotate"
                                                    dur="1.8492753623188404s" repeatCount="indefinite" keyTimes="0;1"
                                                    values="0 50 52;360 50 52">
                                                </animateTransform>
                                            </path>
                                        </svg>
                                    </div>
                                    @if ($createState)
                                        <span>Upload Photo *</span>
                                    @else
                                        <span>Change Photo *</span>
                                    @endif
                                    <input type="file" name="photo" id="profile" wire:model='photo'
                                        accept=".png,.jpg,.jpeg" class="cropit-image-input">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <x-card-footer-button :createState="$createState" :updateState="$updateState" />
            </div>
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
