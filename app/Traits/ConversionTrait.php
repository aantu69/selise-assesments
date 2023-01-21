<?php

namespace App\Traits;

use App\Models\CityCategory;
use App\Models\TaxcardCategory;
use App\Models\DistrictCategory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

trait ConversionTrait
{

    public function convertNumberBangToEng($number)
    {
        $engNumber = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০');
        $converted = str_replace($bangNumber, $engNumber, $number);
        return $converted;
    }
    public function convertNumberEngToBang($number)
    {
        $engNumber = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০');
        $converted = str_replace($engNumber, $bangNumber, $number);
        return $converted;
    }

    public function convertNumberEngToBangWithFormation($number)
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

    public function converToformattedNumber($number)
    {

        if (strlen($number) > 7) {
            $number = substr_replace($number, ',', -3, 0);
            $number = substr_replace($number, ',', -6, 0);
            $number = substr_replace($number, ',', -9, 0);
            return $number;
        }

        if (strlen($number) > 5) {
            $number = substr_replace($number, ',', -3, 0);
            $number = substr_replace($number, ',', -6, 0);
            return $number;
        }

        if (strlen($number) > 3) {
            $number = substr_replace($number, ',', -3, 0);
            return $number;
        }

        return $number;
    }

    public function getMonthYear($month, $year)
    {
        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
            '05' => 'May', '06' => 'Jun', '07' => 'July', '08' => 'August',
            '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December',
        ];
        $month_year = $months[$month] . ', ' . $year;
        return $month_year;
    }
    public function getMonthYearBangla($month, $year)
    {
        $months_bangla = [
            '01' => 'জানুয়ারী', '02' => 'ফেব্রুয়ারী', '03' => 'মার্চ', '04' => 'এপ্রিল',
            '05' => 'মে', '06' => 'জুন', '07' => 'জুলাই', '08' => 'আগস্ট',
            '09' => 'সেপ্টেম্বর', '10' => 'অক্টোবর', '11' => 'নভেম্বর', '12' => 'ডিসেম্বর',
        ];

        $month_year_bangla = $months_bangla[$month] . ', ' . $this->convertNumberEngToBang($year);
        return $month_year_bangla;
    }

    public function getChildCategories($category_name, $category_id)
    {
        if ($category_name == 'taxcard') {
            $category = TaxcardCategory::findOrFail($category_id);
        } elseif ($category_name == 'district') {
            $category = DistrictCategory::findOrFail($category_id);
        } elseif ($category_name == 'city') {
            $category = CityCategory::findOrFail($category_id);
        }

        $cache_name = $category_name . '_category_' . $category->id;

        $category_ids = Cache::rememberForever($cache_name, function () use ($category) {
            $category_ids[] = $category->id;
            if ($category->categories->count() > 0) {
                foreach ($category->categories as $childCategory) {
                    $category_ids[] = $childCategory->id;
                    if ($childCategory->categories->count() > 0) {
                        foreach ($childCategory->categories as $childChildCategory) {
                            $category_ids[] = $childChildCategory->id;
                            if ($childChildCategory->categories->count() > 0) {
                                foreach ($childChildCategory->categories as $childChildChildCategory) {
                                    $category_ids[] = $childChildChildCategory->id;
                                }
                            }
                        }
                    }
                }
            }
            return $category_ids;
        });
        return $category_ids;
    }
}
