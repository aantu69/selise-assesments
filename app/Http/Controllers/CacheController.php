<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;

class CacheController extends Controller
{
    public function index()
    {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:cache');
        Artisan::call('config:cache');
        Artisan::call('view:cache');
        return "Cache is cleared...";
        // return view('home');
    }
    public function storageLink()
    {
        Artisan::call('storage:link');
        return "Storage is linked...";
    }

    public function databaseSeed()
    {
        Artisan::call('php artisan migrate --path=database/migrations/landlord --database=landlord --seed');
        // Artisan::call('php artisan tenants:artisan "migrate:fresh --database=tenant --seed"');
    }
}
