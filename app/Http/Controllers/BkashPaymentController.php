<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class BkashPaymentController extends Controller
{
    private $baseUrl;
    private $tokenUrl;
    private $createUrl;
    private $executeUrl;
    private $queryUrl;
    private $searchUrl;
    private $appKey;
    private $appSecret;
    private $username;
    private $password;
    private $sandbox;

    public function __construct()
    {
        $this->sandbox = 2;
        if ($this->sandbox == 1) {
            // Sandbox-1
            $bkashAppKey = '5tunt4masn6pv2hnvte1sb5n3j';
            $bkashAppSecret = '1vggbqd4hqk9g96o9rrrp2jftvek578v7d2bnerim12a87dbrrka';
            $bkashUsername = 'sandboxTestUser';
            $bkashPassword = 'hWD@8vtzw0';
            $bkashBaseUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta';
        } elseif ($this->sandbox == 2) {
            // Sandbox-2
            $bkashAppKey = '5nej5keguopj928ekcj3dne8p';
            $bkashAppSecret = '1honf6u1c56mqcivtc9ffl960slp4v2756jle5925nbooa46ch62';
            $bkashUsername = 'testdemo';
            $bkashPassword = 'test%#de23@msdao';
            $bkashBaseUrl = 'https://checkout.sandbox.bka.sh/v1.2.0-beta';
        } else {
            // Live Production
            $bkashAppKey = 'PBh5sP4e8D0pfnqeaGqiizCCch';
            $bkashAppSecret = 'kqX1xFBSVQW5BEmuca42lyfBsf4LdcoXEUhiXGhxCsiUNWAFRN6P';
            $bkashUsername = '01841013013';
            $bkashPassword = '6m{eMCWzq9}';
            $bkashBaseUrl = 'https://checkout.pay.bka.sh/v1.2.0-beta';
        }


        $this->baseUrl = $bkashBaseUrl;
        $this->tokenUrl = $bkashBaseUrl . '/checkout/token/grant';
        $this->createUrl = $bkashBaseUrl . '/checkout/payment/create';
        $this->executeUrl = $bkashBaseUrl . '/checkout/payment/execute/';
        $this->queryUrl = $bkashBaseUrl . '/checkout/payment/query/';
        $this->searchUrl = $bkashBaseUrl . '/checkout/payment/search/';
        $this->appKey = $bkashAppKey;
        $this->appSecret = $bkashAppSecret;
        $this->username = $bkashUsername;
        $this->password = $bkashPassword;
    }


    public function getToken()
    {
        session()->forget('bkash_token');

        $postToken = array(
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret
        );

        $url = curl_init($this->tokenUrl);
        $postToken = json_encode($postToken);
        $header = array(
            'Content-Type:application/json',
            "username:" . $this->username,
            "password:" . $this->password
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $postToken);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($url);
        curl_close($url);

        $response = json_decode($result, true);

        if (array_key_exists('msg', $response)) {
            return $response;
        }

        session()->put('bkash_token', $response['id_token']);

        return response()->json(['success', true]);
    }

    public function createPayment(Request $request)
    {
        $token = session()->get('bkash_token');

        $admissionFee = Helper::setting('admission_fee');
        if (((string) $request->amount != (string) $admissionFee)) {
            return response()->json([
                'errorMessage' => 'Not Acceptable. Amount Mismatch!',
                'errorCode' => 2006
            ], 422);
        }

        $request['intent'] = 'sale';
        $request['currency'] = 'BDT';
        $request['merchantInvoiceNumber'] = Helper::generateInvoiceNumber(10);
        // $request['merchantAssociationInfo'] = 'RF23SPRINGIBAJUWMBA123';

        $url = curl_init($this->createUrl);
        $postData = json_encode($request->all());
        $header = array(
            'Content-Type:application/json',
            "authorization:" . $token,
            "x-app-key:" . $this->appKey
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $result = curl_exec($url);
        curl_close($url);
        $results = json_decode($result);
        if (isset($results->paymentID) && isset($results->transactionStatus) && ($results->transactionStatus == 'Initiated')) {
            $this->trackOrderStatus($result);
        }
        return json_decode($result, true);
    }

    public function executePayment(Request $request)
    {
        $token = session()->get('bkash_token');

        $paymentID = $request->paymentID;
        $url = curl_init($this->executeUrl . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:" . $token,
            "x-app-key:" . $this->appKey
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($url);
        curl_close($url);
        $results = json_decode($result);
        if (isset($results->paymentID)) {
            $this->updateOrderStatus($result);
        }
        return json_decode($result, true);
    }

    public function queryPayment(Request $request)
    {
        $token = session()->get('bkash_token');
        // $paymentID = $request->payment_info['payment_id'];
        $paymentID = $_GET['paymentID'];

        $url = curl_init($this->queryUrl . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:" . $token,
            "x-app-key:" . $this->appKey
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($url);
        curl_close($url);
        $results = json_decode($result);
        if (isset($results->paymentID)) {
            $this->updateOrderStatus($result);
        }
        return json_decode($result, true);
    }

    public function searchPayment(Request $request)
    {
        $token = session()->get('bkash_token');
        $trxID = $_GET['trxID'];

        $url = curl_init($this->searchUrl . $trxID);
        $header = array(
            'Content-Type:application/json',
            "authorization:" . $token,
            "x-app-key:" . $this->appKey
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($url);
        curl_close($url);
        return json_decode($result, true);
    }

    protected function trackOrderStatus($result)
    {
        $result = json_decode($result);

        if ($result && $result->paymentID != null && $result->transactionStatus == 'Initiated') {

            $user_id = Auth::check() ? Auth::id() : Session::get('user_id');
            Payment::create([
                'payment_id' => $result->paymentID,
                'amount' => $result->amount,
                'status' => $result->transactionStatus,
                'invoice' => $result->merchantInvoiceNumber,
                'user_id' => $user_id,
            ]);
        }
    }

    protected function updateOrderStatus($result)
    {
        $result = json_decode($result);

        if ($result && $result->paymentID != null && $result->transactionStatus == 'Completed') {

            $user_id = Auth::check() ? Auth::id() : Session::get('user_id');
            $roll_number = $this->generateRollNumber();

            $user = User::find($user_id)->update(['payment' => 1, 'roll_number' => $roll_number]);
            // Payment::create([
            //     'invoice' => $result->merchantInvoiceNumber,
            //     'transaction_id' => $result->trxID,
            //     'amount' => $result->amount,
            //     'user_id' => $user_id,
            // ]);

            $amount = number_format((float)$result->amount, 2, '.', '');

            Payment::updateOrCreate(
                [
                    'payment_id' => $result->paymentID,
                    'amount' => $amount,
                    'invoice' => $result->merchantInvoiceNumber,
                    'user_id' => $user_id
                ],
                [
                    'transaction_id' => $result->trxID,
                    'status' => $result->transactionStatus
                ]
            );
        }
    }

    protected function generateRollNumber()
    {
        $session = Helper::setting('session');
        $startRollNumber = Helper::setting('start_roll_number');

        $allRolls = User::query()
            ->where('session', $session)->where('roll_number', '>', 0)
            ->pluck('roll_number')->map(fn ($item) => (string) $item)->toArray();

        $total = count($allRolls);

        $min = $startRollNumber + 1;
        $max = $startRollNumber + 299;

        if ($total >= 300 && $total < 400) {
            $min = $startRollNumber + 301;
            $max = $startRollNumber + 399;
        }
        if ($total >= 400 && $total < 500) {
            $min = $startRollNumber + 401;
            $max = $startRollNumber + 499;
        }
        if ($total >= 500 && $total < 600) {
            $min = $startRollNumber + 501;
            $max = $startRollNumber + 599;
        }
        if ($total >= 600 && $total < 700) {
            $min = $startRollNumber + 601;
            $max = $startRollNumber + 699;
        }
        if ($total >= 700 && $total < 800) {
            $min = $startRollNumber + 701;
            $max = $startRollNumber + 799;
        }
        if ($total >= 800 && $total < 900) {
            $min = $startRollNumber + 801;
            $max = 899;
        }
        if ($total >= 900 && $total < 1000) {
            $min = $startRollNumber + 901;
            $max = 999;
        }

        do {
            $roll_number = rand($min, $max);
        } while (in_array($roll_number, $allRolls));

        return $roll_number;
    }
}
