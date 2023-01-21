<div class="row">
    <div class="col-lg-12 align-self-center">
        <x-flashMessage />
        <div>
            <div class="card card-default animate__animated animate__fadeInUp">
                <div class="card-header" style="border-bottom: 0; padding:1.5rem 1rem">
                    <h3 class="card-title"><span style="color: red;">* Mandatory Field: MUST be filled up.</span>
                    </h3>
                    @if ($errors->any())
                        <br><br>
                        <div class="alert alert-danger" style="">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Please fill out all required (*) fields.</strong>
                        </div>
                    @endif
                </div>
                <!-- /.card-header -->

                <form autocomplete="off">
                    <div class="card-body">
                        <x-registration-form :createState="$createState" :state="$state" />

                        <!-- /.card-body -->
                        <div class="card-footer" style="border-top: 0;">
                            @if ($errors->any())
                                <div class="alert alert-danger" style="margin-left:-15px;">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Please fill out all required (*) fields.</strong>
                                </div>
                            @endif
                            {!! RecaptchaV3::field('submit') !!}
                            <button id="save" wire:click.prevent="store()"
                                class="registration-submit">Submit</button>
                        </div>
                        <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
</div>
{!! RecaptchaV3::initJs() !!}

@push('scripts')
    <script>
        $(document).ready(function() {
            datepicker();
            setYearMonth();
        });
        document.addEventListener("livewire:load", function(event) {
            Livewire.hook('message.processed', (component) => {
                // datepicker();
                setYearMonth();
            });
        });

        function setYearMonth() {
            $("#job_duration_year").on("change", function(event) {
                if ($(this).val() == '') {
                    $(this).val(0);
                    @this.set('state.job_duration_year', 0);
                }
            });
            $("#job_duration_month").on("change", function(event) {
                if ($(this).val() == '') {
                    $(this).val(0);
                    @this.set('state.job_duration_month', 0);
                }
            });
        }

        function onlyNumberKey(evt) {
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) {
                return false;
            } else {
                return true;
            }
        }

        function datepicker() {
            $('#birth_date').datepicker({
                dateFormat: '{{ config('app.date_format_js') }}',
                changeMonth: true,
                changeYear: true,
                yearRange: "-50:+0",
                showMonthAfterYear: true,
                autoSize: true,
                onSelect: function(selectedDate) {
                    @this.set('state.birth_date', selectedDate);
                }
            });
        }
    </script>
@endpush
