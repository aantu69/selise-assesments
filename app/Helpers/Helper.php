<?php

namespace App\Helpers;

use App\Models\Language;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Helper
{
    public static function getAction($action)
    {
        $action_taken = [
            1 => 'বকেয়া পরিশোধের জন্য তাগাদাপত্র',
            2 => 'আপীলে বিচারাধীন',
            3 => 'ট্রাইব্যুনালে বিচারাধীন',
            4 => 'হাইকোর্ট/সুপ্রিমকোর্টে বিচারাধীন',
            5 => '১৪৩ ধারায় ব্যাংক জব্দ',
            6 => 'সার্টিফিকেট মামলা দায়েরকৃত',
            7 => 'ADR দায়েরকৃত',
            8 => 'অন্যান্য',
        ][$action] ?? '';

        return $action_taken;
    }

    public static function getLevel($level)
    {
        $action_taken = [
            1 => '&nbsp;&nbsp;&nbsp;&nbsp;',
            2 => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
            3 => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
            4 => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
            5 => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
            6 => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
        ][$level] ?? '';

        return $action_taken;
    }

    public static function convertNumberEngToBangWithFormation($number)
    {
        $numbers = explode('.', $number);
        $engNumber = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০');
        // $converted = str_replace($engNumber, $bangNumber, $number);
        // return strlen($numbers[0]);

        if (strlen($numbers[0]) > 7) {
            $number = substr_replace($numbers[0], ',', -3, 0);
            $number = substr_replace($number, ',', -6, 0);
            $number = substr_replace($number, ',', -9, 0);
            if (count($numbers) > 1) {
                $number = $number . '.' . $numbers[1];
            }
            $number = str_replace($engNumber, $bangNumber, $number);
            return $number;
        }

        if (strlen($numbers[0]) > 5) {
            $number = substr_replace($numbers[0], ',', -3, 0);
            $number = substr_replace($number, ',', -6, 0);
            if (count($numbers) > 1) {
                $number = $number . '.' . $numbers[1];
            }
            $number = str_replace($engNumber, $bangNumber, $number);
            return $number;
        }

        if (strlen($numbers[0]) > 3) {
            $number = substr_replace($numbers[0], ',', -3, 0);
            if (count($numbers) > 1) {
                $number = $number . '.' . $numbers[1];
            }
            $number = str_replace($engNumber, $bangNumber, $number);
            return $number;
        }

        // if (count($numbers) > 1) {
        //     $number = $numbers[0] . '.' . $numbers[1];
        // }
        $number = str_replace($engNumber, $bangNumber, $number);
        return $number;
    }

    public static function convertNumberEngToBang($number)
    {
        $engNumber = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০');
        $converted = str_replace($engNumber, $bangNumber, $number);
        return $converted;
    }

    public static function numberFormation($number)
    {
        $numbers = explode('.', $number);

        if (strlen($numbers[0]) > 7) {
            $number = substr_replace($numbers[0], ',', -3, 0);
            $number = substr_replace($number, ',', -6, 0);
            $number = substr_replace($number, ',', -9, 0);
            if (count($numbers) > 1) {
                $number = $number . '.' . $numbers[1];
            }
            return $number;
        }

        if (strlen($numbers[0]) > 5) {
            $number = substr_replace($numbers[0], ',', -3, 0);
            $number = substr_replace($number, ',', -6, 0);
            if (count($numbers) > 1) {
                $number = $number . '.' . $numbers[1];
            }
            return $number;
        }

        if (strlen($numbers[0]) > 3) {
            $number = substr_replace($numbers[0], ',', -3, 0);
            if (count($numbers) > 1) {
                $number = $number . '.' . $numbers[1];
            }
            return $number;
        }

        // if (count($numbers) > 1) {
        //     $number = $numbers[0] . '.' . $numbers[1];
        // }
        return $number;
    }

    public static function generatePassword($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@$%?!')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    public static function generateInvoiceNumber($length, $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
