<?php

namespace App\Traits;

use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

trait ApiTrait
{

    public function callAPI($method, $url, $data, $headers = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!$headers) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json'
            ));
        } else {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        // EXECUTE:
        $result = curl_exec($curl);

        // if (!$result) {
        //     die("Connection Failure");
        // }
        curl_close($curl);
        return $result;
    }

    public function sendSMS($mobile)
    {
        // $url = "https://vas.banglalinkgsm.com/sendSMS/sendSMS?";
        // $mobile = '+88' . $mobile;
        // $code = rand(111111, 999999);
        // $message = 'Verify Code : ' . $code;
        // $data_array =  array(
        //     "userID" => "EDUSMART",
        //     "passwd" => "Smart@12345",
        //     "sender" => "NBR e-TIN",
        //     "msisdn" => $mobile,
        //     "message" => $message,
        // );

        // $data = http_build_query($data_array);
        // $url .= $data;
        //$response = file_get_contents($url);

        $mobile = '+88' . $mobile;
        $code = rand(111111, 999999);
        $message = 'Verify Code : ' . $code;
        $message = str_replace(" ", "+", $message);
        $userid = "EDUSMART";
        $password = "Smart@12345";
        $sender = "NBR e-TIN";
        $sender = str_replace(" ", "+", $sender);
        $url = "https://vas.banglalinkgsm.com/sendSMS/sendSMS?msisdn=$mobile&message=$message&userID=$userid&passwd=$password&sender=$sender";
        // $this->callAPI('GET', $url, false);
        $response = file_get_contents($url);
        return $code;
        // return $url;
    }

    public function getSetApiToken($request)
    {

        $data_array =  array(
            "userId" => "raise_it@xtra",
            "password" => "!Raise1@34"
        );

        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'app_key: 98vPwHhiFB',
            'secret_key: W9NwJCBsZDH7VcEsvpKSBsciNZCxd6d8',
            'companyId: 235'
        );

        $make_call = $this->callAPI('POST', 'http://xtra.beta.aplectrum.com/api/v1/corporate-gift/token', json_encode($data_array), $headers);
        // return $make_call;
        $response = json_decode($make_call, true);
        $token   = 'bearer ' . $response['data']['token'];
        $request->session()->put('token', $token);
        return $request->session()->get('token');
    }

    public function getMerchants($request)
    {
        $headers = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $request->session()->get('token')
        );

        $make_call = $this->callAPI('GET', 'http://xtra.beta.aplectrum.com/api/v1/corporate-gift/merchants', false, $headers);
        // return $make_call;
        $response = json_decode($make_call, true);
        return $response;
    }

    public function getBearerToken($email, $password, $client_secret)
    {
        $url = 'https://api.ritsallnews.com/oauth/token';
        // $url = 'http://localhost:8000/oauth/token';

        // $response = Http::post($url, [
        //     "username" => $email,
        //     "password" => $password,
        //     "grant_type" => "password",
        //     "client_id" => 2,
        //     "client_secret" => $client_secret,
        //     "scope" => "*",
        // ]);
        // $token   = $response->json()['access_token'];
        // return $token;

        $data_array =  array(
            "username" => $email,
            "password" => $password,
            "grant_type" => "password",
            "client_id" => 2,
            "client_secret" => $client_secret,
            "scope" => "*",
        );

        $make_call = $this->callAPI('POST', $url, json_encode($data_array), false);
        $response = json_decode($make_call, true);
        $token   = $response['access_token'];
        return $token;
    }

    public function getAppUser($uid)
    {
        $url = "https://refer.ritsbrowser.com/apiAJ2020/getUserDetailsByUid/" . $uid;
        $make_call = $this->callAPI('GET', $url, false, false);
        // return $make_call;
        $response = json_decode($make_call, true);
        return $response;
    }

    public function getEncryptedData($value, $key)
    {
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = $key;
        $encryption = openssl_encrypt($value, $ciphering, $encryption_key, $options, $encryption_iv);
        return $encryption;
    }

    public function getDecryptedData($value, $key)
    {
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $decryption_iv = '1234567891011121';
        $decryption_key = $key;
        $decryption = openssl_decrypt($value, $ciphering, $decryption_key, $options, $decryption_iv);
        // $decryption = str_replace(' ', '+', $decryption);
        return $decryption;
    }
}
