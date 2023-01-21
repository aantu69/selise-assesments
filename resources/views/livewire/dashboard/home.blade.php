@push('styles')
    <style>
        a {
            color: #555555;
        }
    </style>
@endpush
<div>
    @if ($circles)
        <div class="form-group row">
            <div class="col-md-3">
                <x-select wire:model="selectedCircle" id="selectedCircle" error="selectedCircle" label="সার্কেল">
                    <option value="">সকল সার্কেল</option>
                    @foreach ($circles as $circle)
                        <option value="{{ $circle->id }}">{{ $circle->name_bn }}</option>
                    @endforeach
                </x-select>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-4">
            <a href="#" wire.click.prevent="">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><img
                            src="{{ asset('images/taka.png') }}" /></span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('label.total_due') }}</span>
                        <span
                            class="info-box-number">{{ \App\Helpers\Helper::convertNumberEngToBangWithFormation($due_demands[0]->due_amount / 100) }}</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><img src="{{ asset('images/taka.png') }}" /></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('label.total_disputed_due') }}</span>
                    <span
                        class="info-box-number">{{ \App\Helpers\Helper::convertNumberEngToBangWithFormation($disputed_due_demands[0]->due_amount / 100) }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><img src="{{ asset('images/taka.png') }}" /></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('label.total_undisputed_due') }}</span>
                    <span
                        class="info-box-number">{{ \App\Helpers\Helper::convertNumberEngToBangWithFormation($undisputed_due_demands[0]->due_amount / 100) }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><img src="{{ asset('images/taka.png') }}" /></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('label.total_current_due') }}</span>
                    <span
                        class="info-box-number">{{ \App\Helpers\Helper::convertNumberEngToBangWithFormation($current_demands[0]->due_amount / 100) }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-box">
                <span class="info-box-icon bg-danger elevation-1"><img src="{{ asset('images/taka.png') }}" /></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('label.total_current_disputed_due') }}</span>
                    <span
                        class="info-box-number">{{ \App\Helpers\Helper::convertNumberEngToBangWithFormation($disputed_current_demands[0]->due_amount / 100) }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><img
                        src="{{ asset('images/taka.png') }}" /></span>

                <div class="info-box-content">
                    <span class="info-box-text">{{ __('label.total_current_undisputed_due') }}</span>
                    <span
                        class="info-box-number">{{ \App\Helpers\Helper::convertNumberEngToBangWithFormation($undisputed_current_demands[0]->due_amount / 100) }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Demands (Amount in Taka)</h3>
                        <a href="javascript:void(0);">View Report</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <div class="table-responsive">
                            <table>
                                <tr>
                                    <td style="text-align:left;">Total Demands({{ $previous_tax_year }})</td>
                                    <td style="width:10px;text-align:center;">:</td>
                                    <td style="text-align:right;">
                                        {{ \App\Helpers\Helper::numberFormation($previous_total_demands) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">Total Demands({{ $current_tax_year }})</td>
                                    <td style="width:10px;text-align:center;">:</td>
                                    <td style="text-align:right;">
                                        {{ \App\Helpers\Helper::numberFormation($current_total_demands) }}</td>
                                </tr>
                            </table>
                        </div>
                        <p class="ml-auto d-flex flex-column text-right">
                            @if ($changes_demands > 0)
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i>
                                    {{ number_format((float) $changes_demands, 2, '.', '') }}%
                                </span>
                            @elseif($changes_demands < 0)
                                <span class="text-danger">
                                    <i class="fas fa-arrow-down"></i>
                                    {{ number_format((float) $changes_demands, 2, '.', '') }}%
                                </span>
                            @else
                                <span class="text-info">
                                    <i class="fas fa-arrow-right-arrow-left"></i>
                                    {{ number_format((float) $changes_demands, 2, '.', '') }}%
                                </span>
                            @endif
                            <span class="text-muted">Until today</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="demand-chart" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square red"></i> Last year
                        </span>

                        <span>
                            <i class="fas fa-square green"></i> This year
                        </span>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Payments (Amount in Taka)</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <div class="table-responsive">
                            <table>
                                <tr>
                                    <td style="text-align:left;">Total Collection ({{ $previous_tax_year }})</td>
                                    <td style="width:10px;text-align:center;">:</td>
                                    <td style="text-align:right;">
                                        {{ \App\Helpers\Helper::numberFormation($previous_total_collections) }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">Total Collection ({{ $current_tax_year }})</td>
                                    <td style="width:10px;text-align:center;">:</td>
                                    <td style="text-align:right;">
                                        {{ \App\Helpers\Helper::numberFormation($current_total_collections) }}</td>
                                </tr>
                            </table>
                        </div>
                        <p class="ml-auto d-flex flex-column text-right">
                            @if ($changes_collections > 0)
                                <span class="text-success">
                                    <i class="fas fa-arrow-up"></i>
                                    {{ number_format((float) $changes_collections, 2, '.', '') }}%
                                </span>
                            @elseif($changes_collections < 0)
                                <span class="text-danger">
                                    <i class="fas fa-arrow-down"></i>
                                    {{ number_format((float) $changes_collections, 2, '.', '') }}%
                                </span>
                            @else
                                <span class="text-info">
                                    <i class="fas fa-arrow-right-arrow-left"></i>
                                    {{ number_format((float) $changes_collections, 2, '.', '') }}%
                                </span>
                            @endif
                            <span class="text-muted">Until today</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="payment-chart" height="200"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square blue"></i> Last year
                        </span>

                        <span>
                            <i class="fas fa-square orange"></i> This year
                        </span>
                    </div>
                </div>
            </div>
            <input type="hidden" id="demands_previous" value="{{ $demands_previous }}">
            <input type="hidden" id="demands_current" value="{{ $demands_current }}">
            <input type="hidden" id="collections_previous" value="{{ $collections_previous }}">
            <input type="hidden" id="collections_current" value="{{ $collections_current }}">
            <!-- /.card -->
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        var demandChart;
        var paymentChart;
        $(document).ready(function() {
            generateChart();
        });
        document.addEventListener("livewire:load", function(event) {
            Livewire.hook('message.processed', (component) => {
                demandChart.destroy();
                paymentChart.destroy();
                generateChart();
            });
        });

        function generateChart() {
            var demands_previous = $('#demands_previous').val().replace('[', '').replace(']', '').split(',');
            var demands_current = $('#demands_current').val().replace('[', '').replace(']', '').split(',');
            var collections_previous = $('#collections_previous').val().replace('[', '').replace(']', '').split(
                ',');
            var collections_current = $('#collections_current').val().replace('[', '').replace(']', '').split(',');

            var mode = 'index';
            var month = ['July', 'August', 'September', 'Ooctober', 'November', 'December', 'January',
                'February', 'March', 'April', 'May', 'June'
            ];

            var intersect = true;
            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            };
            var type = 'bar';
            var options = {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        // display: false,
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            callback: function(value) {
                                if (value >= 1000) {
                                    value /= 1000;
                                    value += 'K';
                                }
                                return value;
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            };

            var demandData = {
                labels: month,
                datasets: [{
                        backgroundColor: '#e3342f',
                        borderColor: '#007bff',
                        data: demands_previous
                    },
                    {
                        backgroundColor: '#38c172',
                        borderColor: '#ced4da',
                        data: demands_current
                    }
                ]
            };
            var paymentData = {
                labels: month,
                datasets: [{
                        backgroundColor: '#3490dc',
                        borderColor: '#007bff',
                        data: collections_previous
                    },
                    {
                        backgroundColor: '#f6993f',
                        borderColor: '#ced4da',
                        data: collections_current
                    }
                ]
            };
            var $demandChart = $('#demand-chart');
            var $paymentChart = $('#payment-chart');

            demandChart = new Chart($demandChart, {
                type: type,
                data: demandData,
                options: options
            });

            paymentChart = new Chart($paymentChart, {
                type: type,
                data: paymentData,
                options: options
            });
        }
    </script>
@endpush
