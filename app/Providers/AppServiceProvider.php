<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share categories with all views
        View::composer('*', function ($view) {
            // Get the 4 main categories in EXACT order
            $men = Category::whereNull('parent_id')->where('name', 'Men')->with('children.children')->first();
            $women = Category::whereNull('parent_id')->where('name', 'Women')->with('children.children')->first();
            $accessories = Category::whereNull('parent_id')->where('name', 'Accessories')->with('children.children')->first();
            $footwear = Category::whereNull('parent_id')->where('name', 'Footwear')->with('children.children')->first();

            $rootCategories = collect([
                $men,
                $women,
                $accessories,
                $footwear
            ])->filter()->values();

            $view->with('rootCategories', $rootCategories);
        });
    }
}

