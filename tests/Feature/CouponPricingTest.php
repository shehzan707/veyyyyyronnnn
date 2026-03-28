<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_view_shows_vey70_discount_and_discounted_total(): void
    {
        $cart = [
            'premium-item' => [
                'id' => 1,
                'name' => 'Premium Jacket',
                'price' => 103997,
                'image' => 'test.jpg',
                'quantity' => 1,
                'size' => null,
            ],
        ];

        $response = $this->withSession([
            'cart' => $cart,
            'applied_coupon' => 'VEY70',
        ])->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('Coupon Discount (15% off)', false);
        $response->assertSee('15,600', false);
        $response->assertSee('88,424', false);
    }

    public function test_checkout_store_applies_vey70_discount_to_order_total(): void
    {
        $product = Product::create([
            'name' => 'Premium Jacket',
            'description' => 'Test description',
            'price' => 103997,
            'category' => 'Outerwear',
            'stock' => 10,
        ]);

        $cart = [
            'premium-item' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'image' => 'test.jpg',
                'quantity' => 1,
                'size' => null,
            ],
        ];

        $response = $this->withSession([
            'cart' => $cart,
            'applied_coupon' => 'VEY70',
        ])->post(route('checkout.store'), [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'mobile' => '9999999999',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'pincode' => '123456',
            'address_type' => 'home',
            'payment_method' => 'cod',
        ]);

        $order = Order::query()->firstOrFail();

        $response->assertRedirect(route('order.success', $order->id));
        $this->assertSame('88424.00', $order->fresh()->total_amount);
    }
}
