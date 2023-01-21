<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, noarchive">
    <title>Signature Sheets - Smart Admission</title>
    <!-- Favicons -->
    <link href="{{ asset('images/edu-smart-favicon.jpg') }}" rel="icon" type="image/ico" sizes="32x32">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <style type="text/css">
        #admit_card {
            /* background-color: #EBF8A4; */
            /* border: #000 solid 1px; */
            padding: 5pt;
            margin-top: 40px;
        }

        .admit {
            border: #000 solid 1px;
            /*height:30pt;*/
            margin: 5pt;
            padding: 5pt;
            text-align: left;
        }

        .number {
            list-style-type: decimal;
            margin-left: 0pt;
            padding-left: 15pt;
        }

        .number li {
            padding-top: 2pt;
        }

        ol.sub {
            list-style-type: lower-alpha;
        }

        .print_button {
            background-color: #4A8CF7;
            color: #FFF;
            font-size: 12pt;
            font-weight: normal;
            width: 120pt;
            padding-top: 5pt;
            padding-bottom: 5pt;
            cursor: pointer;
            border: none;
            border-radius: 3pt;
            font-family: "Times New Roman", Times, serif;
        }

        .print_button:hover {
            background-color: #D9342D;
            font-weight: bold;
        }
    </style>
</head>

<body id="my-scrollbar" onload="window.print();">
    <div class="main-banner" id="top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" style="padding-left:0;padding-right:0;">
                    <div style="font-size:20px;font-family:'Poppins', sans-serif;">
                        @php
                            $quantity = 0;
                            $index = 0;
                        @endphp
                        @foreach ($seatPlans as $j => $seatPlan)
                            @php
                                $k = $j == 0 ? 0 : $j - 1;
                                $quantity += $seatPlans[$j]['quantity'];
                                if ($j != 0) {
                                    $index += $seatPlans[$k]['quantity'];
                                }
                            @endphp
                            @for ($i = $index; $i < $quantity; $i += 10)
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <table style="width:100%;font-size:20px;">
                                            <tr>
                                                <td colspan="2" style="text-align:center;">{{ $program }}
                                                    Admission
                                                    Test</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="text-align:center;">{{ $session }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="text-align:center;">{{ $faculty }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="text-align:center;">{{ $institute }}</td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" style="text-align:center;">{{ $testDate }}
                                                    ({{ $testTime }})
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align:left;">Building: {{ $seatPlan->building }}</td>
                                                <td style="text-align:right;">Room: {{ $seatPlan->room_number }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="card-body">
                                        <table style="width:100%;border:1px solid #000;font-size:16px;"
                                            class="table table-bordered data-table incomeTable">
                                            <tr>
                                                <td style="text-align:center;padding-left:10px;">SL No.</td>
                                                <td style="text-align:center;padding-left:10px;">Roll No.</td>
                                                <td style="text-align:center;padding-left:10px;">Photo</td>
                                                <td style="text-align:center;padding-left:10px;">Name</td>
                                                <td style="text-align:center;padding-left:10px;width:40%;">Signature
                                                </td>
                                            </tr>
                                            @foreach ($users->whereBetween('roll_number', [$seatPlan->from_roll, $seatPlan->to_roll]) as $key => $user)
                                                @if ($key >= $i && $key < $i + 10)
                                                    <tr>
                                                        <td style="text-align:center;">{{ $key + 1 - $index }}</td>
                                                        <td style="text-align:center;">{{ $user->roll_number }}</td>
                                                        <td style="text-align:center;">
                                                            <img height="100"
                                                                src="{{ $user->profile->photo_url }}" />
                                                        </td>
                                                        <td>{{ strtoupper($user->name) }}</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                                <div style="page-break-before:always;"></div>
                            @endfor
                        @endforeach


                        {{-- @for ($i = 0; $i < $users->count(); $i += 10)
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <table style="width:100%;font-size:20px;">
                                        <tr>
                                            <td colspan="2" style="text-align:center;">{{ $program }} Admission
                                                Test</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align:center;">{{ $session }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align:center;">{{ $faculty }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="text-align:center;">{{ $institute }}</td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="text-align:center;">{{ $testDate }}
                                                ({{ $testTime }})
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;">Building: FBS</td>
                                            <td style="text-align:right;">Room: 25</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="card-body">
                                    <table style="width:100%;border:1px solid #000;font-size:16px;"
                                        class="table table-bordered data-table incomeTable">
                                        <tr>
                                            <td style="text-align:center;padding-left:10px;">SL No.</td>
                                            <td style="text-align:center;padding-left:10px;">Roll No.</td>
                                            <td style="text-align:center;padding-left:10px;">Photo</td>
                                            <td style="text-align:center;padding-left:10px;">Name</td>
                                            <td style="text-align:center;padding-left:10px;width:40%;">Signature</td>
                                        </tr>
                                        @foreach ($users as $key => $user)
                                            @if ($key < $i + 10 && $key >= $i)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td style="text-align:center;">{{ $user->roll_number }}</td>
                                                    <td style="text-align:center;"><img height="100"
                                                            src="{{ $user->profile->photo_url }}" />
                                                    </td>
                                                    <td>{{ strtoupper($user->name) }}</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                            <div style="page-break-before:always;"></div>
                        @endfor --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
