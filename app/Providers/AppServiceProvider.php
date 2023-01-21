<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\CategoriesComposer;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot()
    {
        Schema::defaultStringLength(125);
        Model::preventLazyLoading(!app()->isProduction());

        // View::composer(['components.categories', 'components.select-categories'], CategoriesComposer::class);

        // $cache_name_categories = 'categories';
        // //Cache::forget($cache_name_taxcard_categories);
        // $categories = Cache::rememberForever($cache_name_categories, function () {
        //     return Category::tree();
        // });

        // dd($categories);
        // View::share('categories', $categories);
    }
}
