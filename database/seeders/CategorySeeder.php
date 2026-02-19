<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $create = function (string $name, string $slug, ?int $parentId, int $sortOrder) {
            return Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => null,
                    'is_active' => true,
                    'sort_order' => $sortOrder,
                    'parent_id' => $parentId,
                ]
            );
        };

        // Top-level Categories
        $men = $create('Men', 'men', null, 1);
        $women = $create('Women', 'women', null, 2);
        $footwear = $create('Footwear', 'footwear', null, 3);
        $accessories = $create('Accessories', 'accessories', null, 4);

        // Men's Categories
        $menApparel = $create('Apparel', 'men-apparel', $men->id, 1);
        $create('Shirts', 'men-shirts', $menApparel->id, 1);
        $create('T-Shirts', 'men-tshirts', $menApparel->id, 2);
        $create('Knitwear', 'men-knitwear', $menApparel->id, 3);
        $create('Jackets & Blazers', 'men-jackets-blazers', $menApparel->id, 4);
        $create('Tailoring', 'men-tailoring', $menApparel->id, 5);

        $menBottoms = $create('Bottoms', 'men-bottoms', $men->id, 2);
        $create('Trousers', 'men-trousers', $menBottoms->id, 1);
        $create('Denim', 'men-denim', $menBottoms->id, 2);
        $create('Shorts', 'men-shorts', $menBottoms->id, 3);

        // Women's Categories
        $womenApparel = $create('Apparel', 'women-apparel', $women->id, 1);
        $create('Tops', 'women-tops', $womenApparel->id, 1);
        $create('Shirts', 'women-shirts', $womenApparel->id, 2);
        $create('Dresses', 'women-dresses', $womenApparel->id, 3);
        $create('Knitwear', 'women-knitwear', $womenApparel->id, 4);

        $womenBottoms = $create('Bottoms', 'women-bottoms', $women->id, 2);
        $create('Trousers', 'women-trousers', $womenBottoms->id, 1);
        $create('Denim', 'women-denim', $womenBottoms->id, 2);
        $create('Skirts', 'women-skirts', $womenBottoms->id, 3);
        $create('Palazzos', 'women-palazzos', $womenBottoms->id, 4);

        // Footwear Categories
        $footwearMen = $create('Men', 'footwear-men', $footwear->id, 1);
        $create('Formal Shoes', 'men-formal-shoes', $footwearMen->id, 1);
        $create('Sneakers', 'men-sneakers', $footwearMen->id, 2);
        $create('Casual Shoes', 'men-casual-shoes', $footwearMen->id, 3);
        $create('Sandals & Slides', 'men-sandals-slides', $footwearMen->id, 4);

        $footwearWomen = $create('Women', 'footwear-women', $footwear->id, 2);
        $create('Heels', 'women-heels', $footwearWomen->id, 1);
        $create('Flats', 'women-flats', $footwearWomen->id, 2);
        $create('Sneakers', 'women-sneakers', $footwearWomen->id, 3);
        $create('Sandals', 'women-sandals', $footwearWomen->id, 4);
    }
}
