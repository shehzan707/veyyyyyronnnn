<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Size;
use App\Models\Category;
use Illuminate\Console\Command;

class UpdateAccessoriesSizes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accessories:update-sizes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all accessory products to have only size "B" (Basic)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $accessoryCategories = [
            'Wallets',
            'Belts',
            'Sunglasses',
            'Caps',
            'Rings',
            'Bracelets',
            'Handbags',
            'Backpacks',
            'Leather Strap',
            'Chain Strap',
        ];

        $this->info('🔄 Starting to update accessory products...');
        $this->line('');

        $updatedCount = 0;
        $errorCount = 0;

        foreach ($accessoryCategories as $categoryName) {
            $category = Category::where('name', $categoryName)->first();
            
            if (!$category) {
                $this->warn("❌ Category '$categoryName' not found!");
                continue;
            }

            // Get all products in this category
            $products = Product::where('category_id', $category->id)->get();

            if ($products->isEmpty()) {
                $this->line("⏭️  No products found in '$categoryName'");
                continue;
            }

            $this->line("📦 Processing '$categoryName' ({$products->count()} products)...");

            foreach ($products as $product) {
                try {
                    // Get current stock (total stock across all sizes)
                    $totalStock = $product->getTotalStock();

                    // Delete all existing size variants
                    Size::where('product_id', $product->id)->delete();

                    // Create new size "B" with the total stock
                    Size::create([
                        'product_id' => $product->id,
                        'size' => 'B',
                        'stock' => $totalStock,
                        'is_available' => $totalStock > 0,
                    ]);

                    // Update product sizes column
                    $product->update([
                        'sizes' => 'B',
                    ]);

                    $this->line("   ✅ {$product->name} - Size changed to 'B' (Stock: {$totalStock})");
                    $updatedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Error updating {$product->name}: {$e->getMessage()}");
                    $errorCount++;
                }
            }

            $this->line('');
        }

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✨ Update Complete!");
        $this->line("✅ Updated: $updatedCount products");
        $this->line("❌ Errors: $errorCount products");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        return 0;
    }
}
