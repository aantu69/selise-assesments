<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Http\Controllers\Controller;

class PrintController extends Controller
{

    public function printAdmitCard()
    {
        $data['user'] = User::with('profile')->find(auth()->id());

        $settings = Setting::all();
        $data['institute'] = $settings->where('key', 'institute')->first()->value;
        $data['faculty'] = $settings->where('key', 'faculty')->first()->value;
        $data['logo'] = $settings->where('key', 'logo')->first()->value;
        $data['program'] = $settings->where('key', 'program')->first()->value;
        $data['session'] = $settings->where('key', 'session')->first()->value;
        $data['testDate'] = $settings->where('key', 'test_date')->first()->value;
        $data['testTime'] = $settings->where('key', 'test_time')->first()->value;
        $data['testTerms'] = $settings->where('key', 'test_terms')->first()->value;
        $data['coordinator'] = $settings->where('key', 'coordinator')->first()->value;
        $data['signature'] = $settings->where('key', 'signature')->first()->value;
        $data['preference'] = $settings->where('key', 'preference')->first()->value;

        if ($data['preference'] == 'Yes') {
            $data['preferences'] = implode(', ', $data['user']->preferenceLists()->pluck('subject')->toArray());
        }
        return view('prints.admit-card', $data);
    }
}
