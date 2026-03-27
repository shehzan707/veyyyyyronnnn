<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardSalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_total_sales_excludes_cancelled_order_items(): void
    {
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'mobile' => '9999999999',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100,
            'category' => 'Test',
            'stock' => 10,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'mobile' => '9999999999',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'pincode' => '123456',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'refund_status' => 'Pending',
            'refund_amount' => 0,
            'total_amount' => 300,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => 'Active Item',
            'price' => 100,
            'quantity' => 2,
            'item_status' => 'Placed',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => 'Cancelled Item',
            'price' => 50,
            'quantity' => 2,
            'item_status' => 'Cancelled',
        ]);

        $response = $this->withSession(['is_admin' => true])->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('totalSales', fn ($totalSales) => (float) $totalSales === 200.0);
    }
}
