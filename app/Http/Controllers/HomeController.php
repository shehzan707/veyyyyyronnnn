<?php

namespace App\Http\Controllers;

use App\Models\MediaFile;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Get posters for Men section
     */
    private function getMenPosters()
    {
        return [
            ["file" => "menjacket.jpg", "line1" => "Define your winter style", "line2" => "Luxury jackets for every look", "link" => route('products.index', ['category' => 'men-jacket'])],
            ["file" => "menshirt.jpg", "line1" => "Effortless elegance daily", "line2" => "Shirts that speak sophistication", "link" => route('products.index', ['category' => 'men-shirt'])],
            ["file" => "mensweatshirt.jpg", "line1" => "Comfort meets couture", "line2" => "Sweatshirts designed to impress", "link" => route('products.index', ['category' => 'men-sweatshirt'])],
            ["file" => "menjean.jpg", "line1" => "Iconic denim, timeless fit", "line2" => "Jeans that move with confidence", "link" => route('products.index', ['category' => 'men-jeans'])],
        ];
    }

    /**
     * Get posters for Women section
     */
    private function getWomenPosters()
    {
        return [
            ["file" => "womenskirt.jpg", "line1" => "Grace in every step", "line2" => "Skirts crafted for modern elegance", "link" => route('products.index', ['category' => 'women-skirts'])],
            ["file" => "womentop.jpg", "line1" => "Minimalism with impact", "line2" => "Tops that elevate your look", "link" => route('products.index', ['category' => 'women-tops'])],
            ["file" => "womensweatshirt.jpg", "line1" => "Chic comfort redefined", "line2" => "Women's sweatshirts with style", "link" => route('products.index', ['category' => 'women-sweatshirt'])],
            ["file" => "womenjeans.jpg", "line1" => "Sculpted for perfection", "line2" => "Women's jeans for every occasion", "link" => route('products.index', ['category' => 'women-bottoms'])],
        ];
    }

    /**
     * Get posters for Accessories section
     */
    private function getAccessoriesPosters()
    {
        return [
            ["file" => "wallets.jpg", "line1" => "Style in every pocket", "line2" => "Premium wallets for modern men and women", "link" => route('products.index', ['category' => 'wallets'])],
            ["file" => "watches.jpg", "line1" => "Timeless elegance", "line2" => "Luxury watches for every occasion", "link" => route('products.index', ['category' => 'watches'])],
            ["file" => "belts.jpg", "line1" => "Perfect fit, perfect style", "line2" => "Designer belts that define your look", "link" => route('products.index', ['category' => 'belts'])],
            ["file" => "sunglasses.jpg", "line1" => "See the world in style", "line2" => "Premium sunglasses with UV protection", "link" => route('products.index', ['category' => 'sunglasses'])],
        ];
    }

    /**
     * Get posters for Men's Footwear section
     */
    private function getMenFootwearPosters()
    {
        return [
            ["file" => "professionalpolish.jpg", "line1" => "Professional Polish", "line2" => "Formal shoes for refined sophistication", "link" => route('products.index', ['category' => 'formal-shoes', 'gender' => 'men'])],
            ["file" => "everydayelegance.jpg", "line1" => "Everyday Elegance", "line2" => "Casual shoes for timeless style", "link" => route('products.index', ['category' => 'casual-shoes', 'gender' => 'men'])],
            ["file" => "urbancomfort.jpg", "line1" => "Urban Comfort", "line2" => "Trendy sneakers for modern life", "link" => route('products.index', ['category' => 'sneakers', 'gender' => 'men'])],
            ["file" => "athleticexcellence.jpg", "line1" => "Athletic Excellence", "line2" => "Performance shoes for active lifestyles", "link" => route('products.index', ['category' => 'sports-shoes', 'gender' => 'men'])],
        ];
    }

    /**
     * Get posters for Women's Footwear section
     */
    private function getWomenFootwearPosters()
    {
        return [
            ["file" => "elegantelevation.jpg", "line1" => "Elegant Elevation", "line2" => "Stunning heels for confident strides", "link" => route('products.index', ['category' => 'heels', 'gender' => 'women'])],
            ["file" => "gracefulsimplicity.jpg", "line1" => "Graceful Simplicity", "line2" => "Comfortable flats with timeless appeal", "link" => route('products.index', ['category' => 'flats', 'gender' => 'women'])],
            ["file" => "casualcharm.jpg", "line1" => "Casual Charm", "line2" => "Trendy sneakers for everyday style", "link" => route('products.index', ['category' => 'sneakers', 'gender' => 'women'])],
            ["file" => "summercomfort.jpg", "line1" => "Summer Comfort", "line2" => "Chic sandals for warm season fashion", "link" => route('products.index', ['category' => 'sandals', 'gender' => 'women'])],
        ];
    }

    /**
     * Get posters for Footwear section (combined men and women)
     */
    private function getFootwearPosters()
    {
        return array_merge(
            $this->getMenFootwearPosters(),
            $this->getWomenFootwearPosters()
        );
    }

    /**
     * Default home page
     */
    public function index()
    {
        $banners = MediaFile::getBySection('default');
        
        $posters = array_merge(
            $this->getMenPosters(),
            $this->getWomenPosters(),
            $this->getAccessoriesPosters(),
            $this->getFootwearPosters()
        );

        return view('shop.home', compact('banners', 'posters'));
    }

    /**
     * Men category home page
     */
    public function homeMen()
    {
        $banners = MediaFile::getBySection('men');
        $posters = $this->getMenPosters();

        return view('shop.home-men', compact('banners', 'posters'));
    }

    /**
     * Women category home page
     */
    public function homeWomen()
    {
        $banners = MediaFile::getBySection('women');
        $posters = $this->getWomenPosters();

        return view('shop.home-women', compact('banners', 'posters'));
    }

    /**
     * Accessories category home page
     */
    public function homeAccessories()
    {
        $banners = MediaFile::getBySection('accessories');
        $posters = $this->getAccessoriesPosters();

        return view('shop.home-accessories', compact('banners', 'posters'));
    }

    /**
     * Footwear category home page
     */
    public function homeFootwear()
    {
        $banners = MediaFile::getBySection('footwear');
        $menPosters = $this->getMenFootwearPosters();
        $womenPosters = $this->getWomenFootwearPosters();

        return view('shop.home-footwear', compact('banners', 'menPosters', 'womenPosters'));
    }
}
