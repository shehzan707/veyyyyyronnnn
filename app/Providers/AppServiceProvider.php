<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use Illuminate\Support\Collection;

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
        // Share categories with layout - only specific root categories in order
        view()->composer('layouts.app', function ($view) {
            $categoryOrder = ['Men', 'Women', 'Accessories', 'Footwear'];
            
            $categories = [];
            foreach ($categoryOrder as $categoryName) {
                $category = Category::whereNull('parent_id')
                    ->where('name', $categoryName)
                    ->with('children')
                    ->first();
                
                if ($category) {
                    $categories[] = $category;
                }
            }
            
            $categories = collect($categories);
            
            $view->with('nav_categories', $categories);
        });
    }
}
