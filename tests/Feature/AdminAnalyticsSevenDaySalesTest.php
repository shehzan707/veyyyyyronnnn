<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAnalyticsSevenDaySalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_page_always_provides_last_seven_days_sales_data(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-03-26 12:00:00'));
        try {
            $user = User::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin.analytics@example.com',
                'mobile' => '9999999999',
                'password' => 'password',
                'role' => 'admin',
                'is_active' => true,
            ]);

            $this->createOrderForDate($user->id, Carbon::parse('2026-03-24 10:00:00'), 90);
            $this->createOrderForDate($user->id, Carbon::parse('2026-03-26 10:00:00'), 150);

            $response = $this->withSession(['is_admin' => true])->get('/admin/analytics?date_from=2026-01-01&date_to=2026-01-02');

            $response->assertOk();
            $response->assertViewHas('sevenDaySales', function ($sevenDaySales) {
                if (count($sevenDaySales) !== 7) {
                    return false;
                }

                $salesByDate = collect($sevenDaySales)->keyBy('date');

                return ($salesByDate['Mar 24']['revenue'] ?? null) === 90.0
                    && ($salesByDate['Mar 26']['revenue'] ?? null) === 150.0
                    && ($salesByDate['Mar 20']['revenue'] ?? null) === 0.0;
            });
        } finally {
            Carbon::setTestNow();
        }
    }

    private function createOrderForDate(int $userId, Carbon $date, float $totalAmount): void
    {
        $order = Order::create([
            'user_id' => $userId,
            'name' => 'Admin User',
            'email' => 'admin.analytics@example.com',
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
            'total_amount' => $totalAmount,
        ]);

        $order->forceFill([
            'created_at' => $date,
            'updated_at' => $date,
        ])->save();
    }
}
