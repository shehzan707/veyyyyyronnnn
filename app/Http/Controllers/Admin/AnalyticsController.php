<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $data = $this->generateAnalyticsData();
        return view('admin.analytics.dashboard', $data);
    }

    public function getAnalyticsData(Request $request)
    {
        $data = $this->generateAnalyticsData($request);
        return response()->json($data);
    }

    private function generateAnalyticsData(Request $request = null)
    {
        $dateFrom = $request ? Carbon::parse($request->date_from ?? Carbon::now()->subDays(30)) : Carbon::now()->subDays(30);
        $dateTo = $request ? Carbon::parse($request->date_to ?? Carbon::now()) : Carbon::now();

        return [
            'kpis' => $this->getKPIs($dateFrom, $dateTo),
            'topProducts' => $this->getTopProducts($dateFrom, $dateTo),
            'topCategories' => $this->getTopCategories($dateFrom, $dateTo),
            'sevenDaySales' => $this->getSevenDaySales(),
            'monthlySales' => $this->getMonthlySales(),
            'yearlySales' => $this->getYearlySales(),
            'revenueVsOrders' => $this->getRevenueVsOrders($dateFrom, $dateTo),
            'customerAnalytics' => $this->getCustomerAnalytics($dateFrom, $dateTo),
            'lowStockProducts' => $this->getLowStockProducts(),
        ];
    }

    private function getKPIs($dateFrom, $dateTo)
    {
        $orders = Order::whereBetween('created_at', [$dateFrom, $dateTo])->get();
        $totalRevenue = $orders->sum('total_amount') * 0.40; // 40% of sales as revenue
        $totalOrders = $orders->count();
        $previousRevenue = Order::whereBetween('created_at', [$dateFrom->copy()->subDays(30), $dateFrom])->sum('total_amount') * 0.40; // 40% of sales
        $revenueGrowth = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue * 100) : 0;

        return [
            'totalRevenue' => round($totalRevenue, 2),
            'totalOrders' => $totalOrders,
            'averageOrderValue' => $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0,
            'revenueGrowth' => round($revenueGrowth, 2),
        ];
    }

    private function getTopProducts($dateFrom, $dateTo)
    {
        $products = OrderItem::selectRaw('product_id, SUM(quantity) as total_quantity, SUM(price * quantity) as total_revenue')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(8)
            ->with('product')
            ->get();

        return $products->map(function ($item) {
            return [
                'name' => $item->product->name ?? 'Unknown Product',
                'quantity' => $item->total_quantity,
                'revenue' => round($item->total_revenue * 0.40, 2), // 40% of sales as revenue
                'image' => $item->product->image ?? null,
            ];
        })->toArray();
    }

    private function getTopCategories($dateFrom, $dateTo)
    {
        $categories = OrderItem::selectRaw('admin_products.category_id, SUM(order_items.quantity) as total_quantity, SUM(order_items.price * order_items.quantity) as total_revenue')
            ->join('admin_products', 'order_items.product_id', '=', 'admin_products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->groupBy('admin_products.category_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->get();

        $data = [];
        foreach ($categories as $cat) {
            $category = Category::find($cat->category_id);
            if ($category) {
                $data[] = [
                    'name' => $category->name,
                    'quantity' => $cat->total_quantity,
                    'revenue' => round($cat->total_revenue * 0.40, 2), // 40% of sales as revenue
                ];
            }
        }
        return $data;
    }

    private function getSevenDaySales()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $data = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $revenue = Order::whereDate('created_at', $date)->sum('total_amount') * 0.40; // 40% of sales as revenue

            $data[] = [
                'date' => $date->format('M d'),
                'day' => $date->format('D'),
                'revenue' => round($revenue, 2),
            ];
        }

        return $data;
    }

    private function getMonthlySales()
    {
        $now = Carbon::now();
        $data = [];

        for ($i = 1; $i <= $now->daysInMonth; $i++) {
            $date = $now->copy()->day($i);
            $revenue = Order::whereDate('created_at', $date)->sum('total_amount') * 0.40; // 40% of sales as revenue
            $data[] = ['day' => $i, 'revenue' => round($revenue, 2), 'date' => $date->format('M d')];
        }

        return $data;
    }

    private function getYearlySales()
    {
        $data = [];

        for ($i = 1; $i <= 12; $i++) {
            $revenue = Order::whereMonth('created_at', $i)->whereYear('created_at', Carbon::now()->year)->sum('total_amount') * 0.40; // 40% of sales as revenue
            $data[] = [
                'month' => Carbon::createFromDate(null, $i, 1)->format('M'),
                'revenue' => round($revenue, 2),
            ];
        }

        return $data;
    }

    private function getRevenueVsOrders($dateFrom, $dateTo)
    {
        $days = [];
        $startDate = Carbon::now()->subDays(6)->startOfDay(); // Last 7 days
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $revenue = Order::whereDate('created_at', $date)->sum('total_amount') * 0.40; // 40% of sales as revenue
            $orders = Order::whereDate('created_at', $date)->count();

            $days[] = [
                'date' => $date->format('M d'),
                'revenue' => round($revenue, 2),
                'orders' => $orders,
            ];
        }

        return $days;
    }

    private function getCustomerAnalytics($dateFrom, $dateTo)
    {
        $totalCustomers = User::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $returning = Order::selectRaw('user_id, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('user_id')
            ->havingRaw('count > 1')
            ->count();
        $new = $totalCustomers - $returning;

        return [
            'total' => $totalCustomers,
            'returning' => $returning,
            'new' => $new,
        ];
    }

    private function getLowStockProducts()
    {
        return Product::where('stock', '<=', 10)
            ->orderBy('stock')
            ->limit(5)
            ->get(['id', 'name', 'stock', 'price'])
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'price' => $product->price,
                ];
            })->toArray();
    }
}
