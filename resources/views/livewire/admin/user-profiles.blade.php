<div>
    <x-flashMessage />
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Update Profile
                    </h3>
                </div>
                <form>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <x-input wire:model.defer="state.name" id="name" error="name" label="Name" required />
                            </div>
                            <div class="col-md-6">
                                <x-input wire:model.defer="state.phone" id="phone" error="phone" label="Phone Number"
                                    required />
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-md-12 profile_pic">
                                        <div class="cropit-overlay"
                                            style="margin-top:10px;width:100px;height:100px;position:absolute;z-index:9999;">
                                        </div>
                                        <div class="cropit-preview" id="imagePreview">
                                            <img src="{{ $state['photo_url'] }}" alt="Profile Picture"
                                                style="width: 100%">
                                        </div>

                                        <span>Image ratio 4:3</span><br>
                                        <span class="btn btn-success fileinput-button">
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
                                                            dur="1.8492753623188404s" repeatCount="indefinite"
                                                            keyTimes="0;1" values="0 50 52;360 50 52">
                                                        </animateTransform>
                                                    </path>
                                                </svg>
                                            </div>
                                            <span id="lfm" data-preview="imagePreview">Change Photo</span>
                                            {{-- <input type="file" name="photo" id="profile" wire:model='photo' class="cropit-image-input"> --}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <x-loading-button wire:click.prevent="update" class="btn-success">Update</x-loading-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            fileManager();
        });

        document.addEventListener("livewire:load", function(event) {
            Livewire.hook('message.processed', (component) => {
                // fileManager();
            });
        });

        function fileManager() {
            $('#lfm').on('click', function() {
                var type = 'image';
                var route_prefix = '/admin/filemanager';
                var target_preview = $('#' + $(this).data('preview'));

                window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=900');
                window.SetUrl = function(items) {
                    var file_path = items.map(function(item) {
                        return item.url;
                    }).join(',');

                    @this.set('state.photo', file_path.split("storage/")[1]);
                    target_preview.html('');
                    var image_path = '';
                    items.forEach(function(item) {
                        image_path = $('<img>').css('width', '290px').attr('src', item.url);
                        @this.set('state.photo_url', item.url);
                    });
                    target_preview.append(image_path);
                    target_preview.trigger('change');
                };
                return false;
            });
        }
    </script>
@endpush
