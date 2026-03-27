<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountOrderPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_view_shows_default_platform_fee_for_existing_orders(): void
    {
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'mobile' => '9999999999',
            'password' => 'password',
            'role' => 'user',
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
            'name' => 'Test User',
            'email' => 'test@example.com',
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
            'total_amount' => 177,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => 'Test Product',
            'price' => 100,
            'quantity' => 1,
            'item_status' => 'Placed',
        ]);

        $response = $this->actingAs($user)->get(route('account.order.view', $order->id));

        $response->assertOk();
        $response->assertSeeInOrder(['Platform Fee', '₹27']);
    }
}
