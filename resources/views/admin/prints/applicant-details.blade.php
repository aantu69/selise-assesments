<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, noarchive">
    <title>Admit Card - Smart Admission</title>
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
                <div class="col-lg-12">
                    <div style="font-size:20px;font-family:'Poppins', sans-serif;">
                        @foreach ($users as $user)
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <table style="width:100%;font-size:20px;">
                                        <tr>
                                            <td style="text-align:center;">{{ $faculty }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;">{{ $institute }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;">{{ $program }} Admission,
                                                {{ $session }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:center;">Applicant's Data Sheet</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="card-body">
                                    <table style="width:100%;border:1px solid #000;font-size:16px;"
                                        class="table table-bordered data-table incomeTable">
                                        <tr>
                                            <td style="text-align:left;padding-left:10px;width:30%;">Roll No.</td>
                                            <td style="text-align:left;padding-left:10px;width:50%;">
                                                {{ $user->roll_number }}</td>
                                            <td style="text-align:left;padding-left:10px;width:20%;" rowspan="5">
                                                <div style="text-align:center;">
                                                    <img width="150"src="{{ $user->profile->photo_url }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding-left:10px;width:30%;">Name</td>
                                            <td style="text-align:left;padding-left:10px;width:50%;">
                                                {{ strtoupper($user->name) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding-left:10px;width:30%;">Mother's Name</td>
                                            <td style="text-align:left;padding-left:10px;width:50%;">
                                                {{ $user->profile->mothers_name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding-left:10px;width:30%;">Father's Name</td>
                                            <td style="text-align:left;padding-left:10px;width:50%;">
                                                {{ $user->profile->fathers_name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding-left:10px;width:30%;">Date of Birth</td>
                                            <td style="text-align:left;padding-left:10px;width:50%;">
                                                {{ $user->profile->birth_date }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding-left:10px;width:30%;">Gender</td>
                                            <td style="text-align:left;padding-left:10px;width:50%;">
                                                {{ $user->profile->gender }}</td>
                                            <td style="text-align:left;padding-left:10px;width:20%;" rowspan="4">
                                                <div style="text-align:center;">
                                                    <img width="150" src="{{ $user->profile->signature_url }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding-left:10px;width:30%;">Nationality</td>
                                            <td style="text-align:left;padding-left:10px;width:50%;">
                                                {{ $user->profile->nationality }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding-left:10px;width:30%;">Mobile</td>
                                            <td style="text-align:left;padding-left:10px;width:50%;">
                                                {{ $user->profile->mobile }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left;padding-left:10px;width:30%;">Email</td>
                                            <td style="text-align:left;padding-left:10px;width:50%;">
                                                {{ $user->email }}</td>
                                        </tr>
                                    </table>

                                    <div style="margin:10px 0px;">Total duration of full-time employment:
                                        {{ $user->profile->job_duration_year }} Years
                                        {{ $user->profile->job_duration_month }} Months
                                    </div>
                                    <span>Education Qualification:</span>
                                    <table style="width:100%;border:1px solid #000;font-size:16px;"
                                        class="table table-bordered data-table incomeTable">
                                        <thead>
                                            <tr>
                                                <th>Exam</th>
                                                <th>Year</th>
                                                <th>Result</th>
                                                <th>Board/ Institute</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->academicQualifications as $academicQualification)
                                                <tr>
                                                    <td>{{ $academicQualification->examination }}</td>
                                                    <td>{{ $academicQualification->passing_year }}</td>
                                                    <td>{{ $academicQualification->result }}</td>
                                                    <td>{{ $academicQualification->institute }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div style="margin-top:20px;">Work Experience:</div>
                                    <table style="width:100%;border:1px solid #000;font-size:16px;margin-bottom:20px;"
                                        class="table table-bordered data-table incomeTable">
                                        <thead>
                                            <tr>
                                                <th>Position</th>
                                                <th>Organization</th>
                                                <th>Responsibility</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->workExperiences as $workExperience)
                                                <tr>
                                                    <td>{{ $workExperience->position }}</td>
                                                    <td>{{ $workExperience->organization }}</td>
                                                    <td>{{ $workExperience->responsibility }}</td>
                                                    <td>{{ $workExperience->start_date . '-' . $workExperience->end_date }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if ($preference == 'Yes')
                                        <div style="">Subject Choice:</div>
                                    @endif
                                    <table style="width:100%;border:1px solid #000;font-size:16px;"
                                        class="table table-bordered data-table incomeTable">
                                        <tbody>
                                            @if ($user->preferences->count() > 0)
                                                <tr>
                                                    @foreach ($user->preferences as $key => $pref)
                                                        @if ($key < 2)
                                                            <td>{{ $pref->subject }}</td>
                                                            <td>{{ $pref->position }}</td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    @foreach ($user->preferences as $key => $pref)
                                                        @if ($key > 1)
                                                            <td>{{ $pref->subject }}</td>
                                                            <td>{{ $pref->position }}</td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="2" align="center" style="vertical-align:bottom;">
                                                    <hr
                                                        style="margin:50pt 0pt 0pt 0pt; width:150pt;height:3px;color:blue;">
                                                    Signature:
                                                    Applicant
                                                </td>
                                                <td colspan="2" align="center" style="vertical-align:bottom;">
                                                    <hr
                                                        style="margin:50pt 0pt 0pt 0pt; width:150pt;height:3px;color:blue;">
                                                    Signature:
                                                    Authority
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div style="page-break-before:always;"></div>
                            @if ($certificate)
                                <div class="card card-primary card-outline">
                                    <div class="card-body" style="text-align: center;">
                                        <iframe src="{{ $user->profile->certificate_url }}" width="800"
                                            height="800"></iframe>
                                    </div>
                                </div>
                                <div style="page-break-before:always;"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
