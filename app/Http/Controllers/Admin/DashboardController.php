<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalSales = OrderItem::notCancelled()
            ->selectRaw('COALESCE(SUM(price * quantity), 0) as total')
            ->value('total');
        $totalProducts = Product::count();
        $totalUsers = User::count();

        $recentOrders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalSales',
            'totalProducts',
            'totalUsers',
            'recentOrders'
        ));
    }
}
