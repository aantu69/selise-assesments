@inject('ConversionTrait', 'App\Traits\ConversionTraitForBlade')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, noarchive">
    <title>Phone Book</title>
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('images/bdgov.png') }}">

    <style>
        @font-face {
            font-family: 'SutonnyMJ';
            src: url('{{ public_path('fonts/sutonny/SutonnyMJ.woff2') }}') format('woff2'),
                url('{{ public_path('fonts/sutonny/SutonnyMJ.woff') }}') format('woff'),
                url('{{ public_path('fonts/sutonny/SutonnyMJ.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Nikosh';
            src: url('{{ public_path('fonts/Nikosh/Nikosh.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        ul li {
            list-style: none;
        }

        table.tbl,
        table.tbl1 {
            border-collapse: collapse;
            font-family: 'Nikosh';
            font-size: 18px;
            line-height: 18px;
            width: 100%;
        }

        table.tbl th {}

        .right {
            text-align: left;
        }

        .left {
            text-align: right;
        }

        table.tbl td,
        table.tbl th {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }

        .eng {
            font-family: sans-serif;
        }

        .borderbottom {
            border-top: none;
            border-left: none;
            border-right: none;
            border-bottom: 1px solid;
        }

        .borderless {
            border: none;
        }

        .zone {
            text-align: center;
            width: 100%;
            margin-top: 15px;
        }

        h3.office {
            font-size: 22px;
            margin-bottom: 0px;
        }

        h4.zone-name {
            font-size: 20px;
            margin-bottom: 0px;
        }

        .detail {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .table {
            margin: 0px 0px 0px 20px;
            width: 93%;
        }

        .table th,
        .table td {
            padding: 1px 2px;
            vertical-align: top;
            border-bottom: 1px solid #dee2e6;
        }

        .txt-left {
            text-align: lrft
        }

        .txt-center {
            text-align: center
        }

        .txt-right {
            text-align: right
        }

        p {
            line-height: 14px;
            margin-bottom: 0px;
        }

        /* @media print {
            table {
                page-break-after: always;
            }
        } */
    </style>
</head>

<body onload="window.print();">
    <div style="width:90%;text-align:center;margin:0 auto;padding-top:30px;">
        <table style="width:100%;text-align:center;" class="tbl1">
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: center;">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">উপ কর কমিশনারের কার্যালয়</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">{{ $circleZone }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">{{ $circle->address }}</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:50%;text-align:left;">নথি নং-{{ $notice->document_number }}</td>
                    <td style="width:50%;text-align:right;">তারিখঃ
                        {{ \App\Helpers\Helper::convertNumberEngToBang($notice->created_date) }} খ্রি</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left;">{!! $notice->to !!}</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left;">বিষয়ঃ {{ $notice->noticeType->subject }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:left;">{!! $notice->noticeType->top_part !!}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table style="width:100%;text-align:center;" class="tbl">
                            <thead>
                                <tr>
                                    <th>ক্রমিক নং</th>
                                    <th>করবর্ষ</th>
                                    <th>মোট আয়কর<br />(আয়কর/সরল সুদ/বিলম্ব সুদ/সারচার্জ/জরিমানা সহ)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($notice->noticeDetails as $key => $demand)
                                    <tr>
                                        <td>{{ \App\Helpers\Helper::convertNumberEngToBang($key + 1) }}</td>
                                        <td>{{ $demand->tax_year }}</td>
                                        <td>{{ \App\Helpers\Helper::convertNumberEngToBangWithFormation($demand->demand_amount) . '/=' }}
                                        </td>
                                    </tr>
                                    @php
                                        $total += $demand->demand_amount;
                                    @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align:center;">মোট</th>
                                    <th style="text-align:center;">
                                        {{ \App\Helpers\Helper::convertNumberEngToBangWithFormation($total) . '/=' }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:justify;">{!! $noticeBottomPart !!}</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>

                <tr>
                    <td style="width:70%;text-align:left;">&nbsp;</td>
                    <td style="width:30%;text-align:center;">({{ $notice->created_by }})</td>
                </tr>
                <tr>
                    <td style="width:70%;text-align:left;">&nbsp;</td>
                    <td style="width:30%;text-align:center;">{{ $notice->designation }}</td>
                </tr>
                <tr>
                    <td style="width:70%;text-align:left;">&nbsp;</td>
                    <td style="width:30%;text-align:center;">{{ $circle->name_bn }}</td>
                </tr>
                <tr>
                    <td style="width:70%;text-align:left;">&nbsp;</td>
                    <td style="width:30%;text-align:center;">{{ $circle->zone->name_bn }}</td>
                </tr>
                <tr>
                    <td style="width:70%;text-align:left;">&nbsp;</td>
                    <td style="width:30%;text-align:center;font-size:12px;">{{ $notice->phone }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
