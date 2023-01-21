<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Circle;
use App\Models\Notice;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class PrintController extends Controller
{

    public function printNotice(Notice $notice)
    {
        $data['notice'] = Notice::with('noticeType', 'noticeDetails')->find($notice->id);
        $data['circle'] = Circle::with('zone')->find(auth()->user()->circles->first()->id);
        $data['circleZone'] = $data['circle']->name_bn . ', ' . $data['circle']->zone->name_bn;
        $noticeBottomPart = Str::of($data['notice']->noticeType->bottom_part)->replace('PYMENT_DUE_DATE', \App\Helpers\Helper::convertNumberEngToBang($data['notice']->payment_date));
        $data['noticeBottomPart'] = Str::of($noticeBottomPart)->replace('CIRCLE_ADDRESS', $data['circleZone']);

        return view('dashboard.prints.notices', $data);
    }
}
