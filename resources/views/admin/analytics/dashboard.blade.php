@extends('layouts.admin')

@section('title', 'Sales Analytics — Admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    background: linear-gradient(135deg, #0f2027 0%, #2c5364 50%, #0f2027 100%) !important;
    min-height: 100vh;
}

html, body {
    height: 100%;
}

.container {
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
}

.analytics-container {
    padding: 30px;
    background: linear-gradient(135deg, #0f2027 0%, #2c5364 50%, #0f2027 100%);
    min-height: calc(100vh - 80px);
    height: auto;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    width: 100%;
    margin: 0;
}

.analytics-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.analytics-header h1 {
    color: #fff;
    font-size: 2.5rem;
    font-weight: 700;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.filter-controls {
    display: flex;
    gap: 15px;
    align-items: center;
}

.filter-btn, .export-btn {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.filter-btn:hover, .export-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}

/* KPI Cards */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.kpi-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 16px;
    padding: 25px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #2563eb);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}

.kpi-card:hover {
    background: rgba(255, 255, 255, 0.12);
    transform: translateY(-5px);
    border-color: rgba(59, 130, 246, 0.3);
}

.kpi-card:hover::before {
    transform: scaleX(1);
}

.kpi-label {
    font-size: 0.9rem;
    color: #cbd5e1;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.kpi-value {
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 8px;
}

.kpi-growth {
    font-size: 0.85rem;
    color: #10b981;
    font-weight: 600;
}

.kpi-growth.negative {
    color: #ef4444;
}

/* Charts Grid */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.chart-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 16px;
    padding: 25px;
    transition: all 0.3s ease;
}

.chart-card:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(59, 130, 246, 0.3);
}

.chart-title {
    color: #fff;
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.chart-icon {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.8rem;
}

.chart-container {
    position: relative;
    height: 350px;
}

.chart-container canvas {
    max-height: 350px;
}

/* Full Width Charts */
.full-width-chart {
    grid-column: 1 / -1;
}

/* Table Styles */
.products-table {
    width: 100%;
    border-collapse: collapse;
}

.products-table thead {
    background: rgba(59, 130, 246, 0.1);
}

.products-table th {
    color: #cbd5e1;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
}

.products-table td {
    color: #e2e8f0;
    padding: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.products-table tbody tr:hover {
    background: rgba(59, 130, 246, 0.1);
}

.product-badge {
    display: inline-block;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-active {
    background: linear-gradient(135deg, #10b981, #059669);
}

.status-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

.status-critical {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

/* Responsive */
@media (max-width: 1200px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .kpi-grid {
        grid-template-columns: 1fr;
    }
    
    .analytics-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .filter-controls {
        width: 100%;
        flex-wrap: wrap;
    }
}

/* Animations */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chart-card, .kpi-card {
    animation: slideIn 0.5s ease forwards;
}

.chart-card:nth-child(2) { animation-delay: 0.1s; }
.chart-card:nth-child(3) { animation-delay: 0.2s; }
.chart-card:nth-child(4) { animation-delay: 0.3s; }

/* Loading State */
.loading-spinner {
    border: 3px solid rgba(255, 255, 255, 0.1);
    border-top: 3px solid #3b82f6;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@section('content')
<div class="analytics-container">
    <!-- Header -->
    <div class="analytics-header">
        <div>
            <h1>📊 Sales Analytics</h1>
            <p style="color: #cbd5e1; margin-top: 5px;">Real-time business insights & performance metrics</p>
        </div>
        <div class="filter-controls">
            <input type="date" id="dateFrom" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 10px; border-radius: 8px;">
            <input type="date" id="dateTo" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 10px; border-radius: 8px;">
            <button class="filter-btn" onclick="applyFilters()">🔍 Apply</button>
            <button class="export-btn" onclick="exportToPDF()">📥 Export PDF</button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">💰 Total Revenue</div>
            <div class="kpi-value">₹<span id="totalRevenue">{{ number_format($kpis['totalRevenue'], 2) }}</span></div>
            <div class="kpi-growth {{ $kpis['revenueGrowth'] >= 0 ? '' : 'negative' }}">
                {{ $kpis['revenueGrowth'] >= 0 ? '↑' : '↓' }} {{ abs($kpis['revenueGrowth']) }}% vs last month
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">📦 Total Orders</div>
            <div class="kpi-value">{{ $kpis['totalOrders'] }}</div>
            <div class="kpi-growth">This period</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">🛒 Avg Order Value</div>
            <div class="kpi-value">₹{{ number_format($kpis['averageOrderValue'], 2) }}</div>
            <div class="kpi-growth">Per transaction</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">📈 Growth Rate</div>
            <div class="kpi-value {{ $kpis['revenueGrowth'] >= 0 ? '' : 'negative' }}">{{ $kpis['revenueGrowth'] }}%</div>
            <div class="kpi-growth">Month-on-month</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Daily Sales -->
        <div class="chart-card full-width-chart">
            <div class="chart-title">
                <div class="chart-icon">📅</div>
                Daily Sales: Today vs Yesterday
            </div>
            <div class="chart-container">
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="chart-card">
            <div class="chart-title">
                <div class="chart-icon">🏆</div>
                Top Selling Products
            </div>
            <div class="chart-container">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        <!-- Top Categories -->
        <div class="chart-card">
            <div class="chart-title">
                <div class="chart-icon">🎯</div>
                Category Distribution
            </div>
            <div class="chart-container">
                <canvas id="topCategoriesChart"></canvas>
            </div>
        </div>

        <!-- Monthly Sales -->
        <div class="chart-card">
            <div class="chart-title">
                <div class="chart-icon">📊</div>
                Monthly Sales Trend
            </div>
            <div class="chart-container">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Yearly Sales -->
        <div class="chart-card">
            <div class="chart-title">
                <div class="chart-icon">📈</div>
                Yearly Revenue
            </div>
            <div class="chart-container">
                <canvas id="yearlySalesChart"></canvas>
            </div>
        </div>

        <!-- Revenue vs Orders -->
        <div class="chart-card">
            <div class="chart-title">
                <div class="chart-icon">💹</div>
                Revenue vs Orders
            </div>
            <div class="chart-container">
                <canvas id="revenueVsOrdersChart"></canvas>
            </div>
        </div>

        <!-- Customer Analytics -->
        <div class="chart-card">
            <div class="chart-title">
                <div class="chart-icon">👥</div>
                Customer Breakdown
            </div>
            <div class="chart-container">
                <canvas id="customerAnalyticsChart"></canvas>
            </div>
        </div>

        <!-- Low Stock Products Table -->
        <div class="chart-card full-width-chart">
            <div class="chart-title">
                <div class="chart-icon">⚠️</div>
                Low Stock High-Demand Products
            </div>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Current Stock</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="lowStockBody">
                    @foreach($lowStockProducts as $product)
                    <tr>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['stock'] }} units</td>
                        <td>₹{{ number_format($product['price'], 2) }}</td>
                        <td>
                            <span class="product-badge {{ $product['stock'] <= 5 ? 'status-critical' : 'status-warning' }}">
                                {{ $product['stock'] <= 5 ? '🔴 Critical' : '🟡 Warning' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
<script src="https://html2pdf.github.io/html2pdf.bundle.min.js"></script>

<script>
const analyticsData = {
    topProducts: @json($topProducts),
    topCategories: @json($topCategories),
    dailySales: @json($dailySales),
    monthlySales: @json($monthlySales),
    yearlySales: @json($yearlySales),
    revenueVsOrders: @json($revenueVsOrders),
    customerAnalytics: @json($customerAnalytics),
};

// Color schemes
const colors = {
    primary: 'rgba(59, 130, 246, 0.8)',
    primaryLight: 'rgba(59, 130, 246, 0.2)',
    secondary: 'rgba(10, 184, 181, 0.8)',
    secondaryLight: 'rgba(10, 184, 181, 0.2)',
    danger: 'rgba(239, 68, 68, 0.8)',
    dangerLight: 'rgba(239, 68, 68, 0.2)',
    warning: 'rgba(245, 158, 11, 0.8)',
    success: 'rgba(16, 185, 129, 0.8)',
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: { color: '#cbd5e1', font: { size: 12 } }
        }
    },
    scales: {
        x: {
            ticks: { color: '#cbd5e1' },
            grid: { color: 'rgba(255, 255, 255, 0.05)' }
        },
        y: {
            ticks: { color: '#cbd5e1' },
            grid: { color: 'rgba(255, 255, 255, 0.05)' }
        }
    }
};

// Daily Sales Chart
new Chart(document.getElementById('dailySalesChart'), {
    type: 'line',
    data: {
        labels: analyticsData.dailySales.today.map(d => d.hour),
        datasets: [
            {
                label: 'Today',
                data: analyticsData.dailySales.today.map(d => d.revenue),
                borderColor: colors.primary,
                backgroundColor: colors.primaryLight,
                borderWidth: 2,
                fill: true,
                tension: 0.4
            },
            {
                label: 'Yesterday',
                data: analyticsData.dailySales.yesterday.map(d => d.revenue),
                borderColor: colors.secondary,
                backgroundColor: colors.secondaryLight,
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }
        ]
    },
    options: { ...chartOptions, scales: { ...chartOptions.scales, y: { ...chartOptions.scales.y, ticks: { ...chartOptions.scales.y.ticks, callback: v => '₹' + v } } } }
});

// Top Products Chart
new Chart(document.getElementById('topProductsChart'), {
    type: 'bar',
    data: {
        labels: analyticsData.topProducts.map(p => p.name.substring(0, 15)),
        datasets: [{
            label: 'Revenue (₹)',
            data: analyticsData.topProducts.map(p => p.revenue),
            backgroundColor: [colors.primary, colors.secondary, colors.warning, colors.success, colors.danger, colors.primary, colors.secondary, colors.warning],
            borderRadius: 8
        }]
    },
    options: { ...chartOptions, indexAxis: 'y' }
});

// Top Categories Chart
new Chart(document.getElementById('topCategoriesChart'), {
    type: 'doughnut',
    data: {
        labels: analyticsData.topCategories.map(c => c.name),
        datasets: [{
            data: analyticsData.topCategories.map(c => c.revenue),
            backgroundColor: [colors.primary, colors.secondary, colors.warning, colors.success, colors.danger],
            borderColor: 'rgba(15, 23, 42, 1)',
            borderWidth: 2
        }]
    },
    options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { ...chartOptions.plugins.legend, position: 'bottom' } } }
});

// Monthly Sales Chart
new Chart(document.getElementById('monthlySalesChart'), {
    type: 'area',
    data: {
        labels: analyticsData.monthlySales.map(m => m.date),
        datasets: [{
            label: 'Daily Sales (₹)',
            data: analyticsData.monthlySales.map(m => m.revenue),
            borderColor: colors.primary,
            backgroundColor: colors.primaryLight,
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: chartOptions
});

// Yearly Sales Chart
new Chart(document.getElementById('yearlySalesChart'), {
    type: 'bar',
    data: {
        labels: analyticsData.yearlySales.map(y => y.month),
        datasets: [{
            label: 'Monthly Revenue (₹)',
            data: analyticsData.yearlySales.map(y => y.revenue),
            backgroundColor: colors.primary,
            borderRadius: 8
        }]
    },
    options: chartOptions
});

// Revenue vs Orders Chart
new Chart(document.getElementById('revenueVsOrdersChart'), {
    type: 'bar',
    data: {
        labels: analyticsData.revenueVsOrders.map(r => r.date),
        datasets: [
            {
                label: 'Revenue (₹)',
                data: analyticsData.revenueVsOrders.map(r => r.revenue),
                backgroundColor: colors.primary,
                borderRadius: 8,
                yAxisID: 'y'
            },
            {
                label: 'Orders',
                data: analyticsData.revenueVsOrders.map(r => r.orders),
                backgroundColor: colors.secondary,
                borderRadius: 8,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        ...chartOptions,
        scales: {
            y: { ...chartOptions.scales.y, position: 'left' },
            y1: { ...chartOptions.scales.y, position: 'right', grid: { display: false } }
        }
    }
});

// Customer Analytics Chart
new Chart(document.getElementById('customerAnalyticsChart'), {
    type: 'pie',
    data: {
        labels: ['Returning Customers', 'New Customers'],
        datasets: [{
            data: [analyticsData.customerAnalytics.returning, analyticsData.customerAnalytics.new],
            backgroundColor: [colors.primary, colors.secondary],
            borderColor: 'rgba(15, 23, 42, 1)',
            borderWidth: 2
        }]
    },
    options: { ...chartOptions, plugins: { ...chartOptions.plugins, legend: { ...chartOptions.plugins.legend, position: 'bottom' } } }
});

// Apply Filters
function applyFilters() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    if (dateFrom && dateTo) {
        window.location.href = `/admin/analytics?date_from=${dateFrom}&date_to=${dateTo}`;
    }
}

// Export to PDF
function exportToPDF() {
    const element = document.querySelector('.analytics-container');
    const opt = {
        margin: 10,
        filename: 'sales-analytics-' + new Date().toISOString().split('T')[0] + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { orientation: 'landscape', unit: 'mm', format: 'a4' }
    };
    html2pdf().set(opt).save().from(element).save();
}
</script>
@endpush
