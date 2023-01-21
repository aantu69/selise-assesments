<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
use App\Exports\UsersExport;

use Illuminate\Http\Request;;

use App\Http\Controllers\Controller;
use App\Models\SeatPlan;
use Maatwebsite\Excel\Facades\Excel;

class PrintController extends Controller
{

    public function printApplicants(Request $request)
    {
        $data['users'] = User::with('profile', 'academicQualifications', 'workExperiences', 'preferences')
            ->where([['payment', 1], ['roll_number', '!=', 0]])
            ->orderBy('roll_number', 'asc')
            ->get();

        // dd($data['users']);

        $settings = Setting::all();
        $data['institute'] = $settings->where('key', 'institute')->first()->value;
        $data['faculty'] = $settings->where('key', 'faculty')->first()->value;
        $data['program'] = $settings->where('key', 'program')->first()->value;
        $data['session'] = $settings->where('key', 'session')->first()->value;
        $data['preference'] = $settings->where('key', 'preference')->first()->value;
        $data['certificate'] = $request->certificate;

        return view('admin.prints.applicant-details', $data);
    }

    public function printSignatureSheets(Request $request)
    {
        $data['users'] = User::with('profile', 'academicQualifications', 'workExperiences', 'preferences')
            ->where([['payment', 1], ['roll_number', '!=', 0]])
            ->orderBy('roll_number', 'asc')
            ->get();

        $data['seatPlans'] = SeatPlan::all();

        $settings = Setting::all();
        $data['institute'] = $settings->where('key', 'institute')->first()->value;
        $data['faculty'] = $settings->where('key', 'faculty')->first()->value;
        $data['program'] = $settings->where('key', 'program')->first()->value;
        $data['session'] = $settings->where('key', 'session')->first()->value;
        $data['preference'] = $settings->where('key', 'preference')->first()->value;
        $data['testDate'] = $settings->where('key', 'test_date')->first()->value;
        $data['testTime'] = $settings->where('key', 'test_time')->first()->value;
        $data['certificate'] = $request->certificate;

        return view('admin.prints.signature-sheets', $data);
    }

    public function printSeatPlans()
    {
        $data['seatPlans'] = SeatPlan::all();

        $settings = Setting::all();
        $data['institute'] = $settings->where('key', 'institute')->first()->value;
        $data['faculty'] = $settings->where('key', 'faculty')->first()->value;
        $data['program'] = $settings->where('key', 'program')->first()->value;
        $data['session'] = $settings->where('key', 'session')->first()->value;
        $data['preference'] = $settings->where('key', 'preference')->first()->value;
        $data['testDate'] = $settings->where('key', 'test_date')->first()->value;
        $data['testTime'] = $settings->where('key', 'test_time')->first()->value;

        return view('admin.prints.seat-plans', $data);
    }

    public function excelApplicants()
    {
        return Excel::download(new UsersExport, 'applicants.xlsx');
    }
}
