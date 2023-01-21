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
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <table style="width:100%;font-size:20px;">
                                    <tr>
                                        <td style="text-align:center;">{{ $program }}
                                            Admission
                                            Test</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">{{ $session }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">{{ $faculty }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">{{ $institute }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">{{ $testDate }}
                                            ({{ $testTime }})
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">Detail Seat Plan</td>
                                    </tr>

                                </table>
                            </div>
                            <div class="card-body">
                                <table style="width:100%;border:1px solid #000;font-size:16px;"
                                    class="table table-bordered data-table incomeTable">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;padding-left:10px;">Room No.</th>
                                            <th style="text-align:center;padding-left:10px;">Building</th>
                                            <th style="text-align:center;padding-left:10px;">Quantity</th>
                                            <th style="text-align:center;padding-left:10px;">Roll Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($seatPlans as $j => $seatPlan)
                                            <tr>
                                                <td style="text-align:center;">{{ $seatPlan->room_number }}</td>
                                                <td style="text-align:center;">{{ $seatPlan->building }}</td>
                                                <td style="text-align:center;">{{ $seatPlan->quantity }}</td>
                                                <td style="text-align:center;">
                                                    {{ $seatPlan->from_roll . '-' . $seatPlan->to_roll }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div style="page-break-before:always;"></div>
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <table style="width:100%;font-size:20px;">
                                    <tr>
                                        <td style="text-align:center;">{{ $program }}
                                            Admission
                                            Test</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">{{ $session }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">{{ $faculty }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">{{ $institute }}</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">{{ $testDate }}
                                            ({{ $testTime }})
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">OMR & Question Receive</td>
                                    </tr>

                                </table>
                            </div>
                            <div class="card-body">
                                <table style="width:100%;border:1px solid #000;font-size:16px;"
                                    class="table table-bordered data-table incomeTable">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;padding-left:10px;">Room No.</th>
                                            <th style="text-align:center;padding-left:10px;">Building</th>
                                            <th style="text-align:center;padding-left:10px;">Quantity</th>
                                            <th style="text-align:center;padding-left:10px;">Receive Signature</th>
                                            <th style="text-align:center;padding-left:10px;">Number of Presence</th>
                                            <th style="text-align:center;padding-left:10px;">Return Signature</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($seatPlans as $j => $seatPlan)
                                            <tr>
                                                <td style="text-align:center;">{{ $seatPlan->room_number }}</td>
                                                <td style="text-align:center;">{{ $seatPlan->building }}</td>
                                                <td style="text-align:center;">&nbsp;</td>
                                                <td style="text-align:center;">&nbsp;</td>
                                                <td style="text-align:center;">&nbsp;</td>
                                                <td style="text-align:center;">&nbsp;</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
