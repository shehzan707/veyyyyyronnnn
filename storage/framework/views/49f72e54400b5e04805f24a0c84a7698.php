<?php $__env->startSection('title', 'Sales Analytics — Admin'); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    background: #1a1a1a !important;
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
    background: #1a1a1a;
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
    color: #3b82f6;
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

.sales-pulse-card {
    background:
        radial-gradient(circle at top, rgba(52, 245, 176, 0.18) 0%, rgba(5, 12, 20, 0.96) 44%, rgba(3, 7, 12, 0.98) 100%);
    border-color: rgba(52, 245, 176, 0.28);
    box-shadow:
        inset 0 0 0 1px rgba(52, 245, 176, 0.08),
        0 18px 40px rgba(0, 0, 0, 0.32),
        0 0 28px rgba(52, 245, 176, 0.08);
    overflow: hidden;
    position: relative;
}

.sales-pulse-card::before {
    content: '';
    inset: 0;
    opacity: 0.5;
    pointer-events: none;
    position: absolute;
    background-image:
        linear-gradient(rgba(52, 245, 176, 0.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(52, 245, 176, 0.08) 1px, transparent 1px);
    background-size: 26px 26px;
}

.sales-pulse-card .chart-title {
    position: relative;
    z-index: 1;
}

.sales-pulse-card .chart-title small {
    color: #86efac;
    display: block;
    font-size: 0.82rem;
    font-weight: 500;
    margin-top: 4px;
    text-transform: none;
}

.sales-pulse-card .chart-icon {
    background: linear-gradient(135deg, #34f5b0, #10b981);
    box-shadow: 0 0 18px rgba(52, 245, 176, 0.35);
}

.sales-pulse-container {
    background: linear-gradient(180deg, rgba(6, 14, 22, 0.78), rgba(3, 8, 14, 0.96));
    border: 1px solid rgba(52, 245, 176, 0.16);
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    min-height: 390px;
    padding: 12px;
}

.sales-pulse-container canvas {
    position: relative;
    z-index: 1;
}

.sales-pulse-visual {
    min-height: 300px;
    position: relative;
}

.sales-pulse-svg {
    display: block;
    height: 100%;
    width: 100%;
}

.sales-pulse-axis-labels {
    display: grid;
    gap: 10px;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    margin-top: 14px;
    position: relative;
    z-index: 1;
}

.sales-pulse-axis-item {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(52, 245, 176, 0.1);
    border-radius: 10px;
    padding: 10px 8px;
    text-align: center;
}

.sales-pulse-axis-day {
    color: #86efac;
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 4px;
    text-transform: uppercase;
}

.sales-pulse-axis-value {
    color: #e2fdf3;
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
}

.neon-chart-card {
    background:
        radial-gradient(circle at top, rgba(52, 245, 176, 0.12) 0%, rgba(7, 14, 24, 0.95) 42%, rgba(3, 7, 12, 0.98) 100%);
    border-color: rgba(52, 245, 176, 0.16);
    box-shadow:
        inset 0 0 0 1px rgba(52, 245, 176, 0.06),
        0 18px 40px rgba(0, 0, 0, 0.26);
    overflow: hidden;
    position: relative;
}

.neon-chart-card::before {
    content: '';
    inset: 0;
    opacity: 0.3;
    pointer-events: none;
    position: absolute;
    background-image:
        linear-gradient(rgba(52, 245, 176, 0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(52, 245, 176, 0.04) 1px, transparent 1px);
    background-size: 28px 28px;
}

.neon-chart-card .chart-title {
    position: relative;
    z-index: 1;
}

.neon-chart-card .chart-title small {
    color: #86efac;
    display: block;
    font-size: 0.8rem;
    font-weight: 500;
    margin-top: 4px;
    text-transform: none;
}

.neon-chart-body {
    display: flex;
    flex-direction: column;
    gap: 14px;
    min-height: 300px;
    position: relative;
    z-index: 1;
}

.neon-chart-card .chart-container {
    height: auto;
    min-height: 300px;
    position: relative;
    z-index: 1;
}

#topProductsChart,
#topCategoriesChart,
#monthlySalesChart,
#yearlySalesChart,
#revenueVsOrdersChart,
#customerAnalyticsChart {
    display: none !important;
}

.metric-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.metric-row {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(52, 245, 176, 0.1);
    border-radius: 12px;
    padding: 12px;
}

.metric-row-top {
    align-items: center;
    display: flex;
    gap: 12px;
    justify-content: space-between;
}

.metric-product {
    align-items: center;
    display: flex;
    gap: 12px;
    min-width: 0;
}

.metric-thumb {
    align-items: center;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(52, 245, 176, 0.12);
    border-radius: 10px;
    display: flex;
    flex: 0 0 48px;
    height: 48px;
    justify-content: center;
    overflow: hidden;
    position: relative;
    width: 48px;
}

.metric-thumb img {
    display: block;
    height: 100%;
    object-fit: cover;
    width: 100%;
}

.metric-thumb-fallback {
    align-items: center;
    color: #86efac;
    display: none;
    font-size: 0.68rem;
    font-weight: 700;
    height: 100%;
    justify-content: center;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    width: 100%;
}

.metric-thumb.no-image .metric-thumb-fallback {
    display: flex;
}

.metric-text {
    min-width: 0;
}

.metric-name {
    color: #f8fafc;
    font-size: 0.92rem;
    font-weight: 600;
}

.metric-sub {
    color: #86efac;
    font-size: 0.76rem;
    margin-top: 4px;
}

.metric-value {
    color: #d1fae5;
    font-size: 0.9rem;
    font-weight: 700;
    white-space: nowrap;
}

.metric-track {
    background: rgba(255, 255, 255, 0.06);
    border-radius: 999px;
    height: 8px;
    margin-top: 10px;
    overflow: hidden;
}

.metric-fill {
    background: linear-gradient(90deg, #34f5b0, #6ee7b7);
    border-radius: 999px;
    box-shadow: 0 0 14px rgba(52, 245, 176, 0.3);
    height: 100%;
}

.mini-chart-surface {
    background: linear-gradient(180deg, rgba(6, 14, 22, 0.68), rgba(3, 8, 14, 0.94));
    border: 1px solid rgba(52, 245, 176, 0.1);
    border-radius: 14px;
    min-height: 230px;
    padding: 10px;
}

.mini-chart-svg {
    display: block;
    height: 100%;
    width: 100%;
}

.chart-summary-grid {
    display: grid;
    gap: 10px;
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.chart-summary-chip {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(52, 245, 176, 0.08);
    border-radius: 12px;
    padding: 10px;
}

.chart-summary-chip span {
    color: #86efac;
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 4px;
    text-transform: uppercase;
}

.chart-summary-chip strong {
    color: #f8fafc;
    display: block;
    font-size: 0.92rem;
}

.chart-summary-chip em {
    color: #cbd5e1;
    display: block;
    font-size: 0.75rem;
    font-style: normal;
    margin-top: 3px;
}

.year-bars {
    align-items: end;
    display: grid;
    gap: 10px;
    grid-template-columns: repeat(12, minmax(0, 1fr));
    min-height: 230px;
}

.year-bar-item {
    align-items: center;
    display: flex;
    flex-direction: column;
    gap: 8px;
    height: 100%;
}

.year-bar-track {
    align-items: end;
    background: rgba(255, 255, 255, 0.06);
    border-radius: 999px 999px 12px 12px;
    display: flex;
    height: 190px;
    justify-content: center;
    padding: 8px 0;
    width: 100%;
}

.year-bar-fill {
    background: linear-gradient(180deg, #6ee7b7, #34f5b0);
    border-radius: 999px;
    box-shadow: 0 0 18px rgba(52, 245, 176, 0.24);
    min-height: 8px;
    width: 70%;
}

.year-bar-label {
    color: #cbd5e1;
    font-size: 0.74rem;
    font-weight: 700;
    text-transform: uppercase;
}

.compare-grid {
    align-items: end;
    display: grid;
    gap: 10px;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    min-height: 230px;
}

.compare-group {
    align-items: center;
    display: flex;
    flex-direction: column;
    gap: 8px;
    height: 100%;
}

.compare-bars {
    align-items: end;
    display: flex;
    gap: 5px;
    height: 180px;
    width: 100%;
}

.compare-bar {
    border-radius: 999px 999px 10px 10px;
    min-height: 8px;
    width: 50%;
}

.compare-bar.revenue {
    background: linear-gradient(180deg, #34f5b0, #10b981);
    box-shadow: 0 0 16px rgba(52, 245, 176, 0.22);
}

.compare-bar.orders {
    background: linear-gradient(180deg, #60a5fa, #2563eb);
}

.compare-label {
    color: #cbd5e1;
    font-size: 0.74rem;
    font-weight: 700;
}

.compare-legend {
    display: flex;
    gap: 18px;
    margin-top: auto;
}

.compare-legend span {
    align-items: center;
    color: #cbd5e1;
    display: inline-flex;
    font-size: 0.78rem;
    gap: 7px;
}

.compare-legend i {
    border-radius: 999px;
    display: inline-block;
    height: 10px;
    width: 10px;
}

.compare-legend .legend-revenue {
    background: #34f5b0;
}

.compare-legend .legend-orders {
    background: #60a5fa;
}

.customer-stack {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 999px;
    display: flex;
    height: 18px;
    overflow: hidden;
}

.customer-stack-fill {
    height: 100%;
}

.customer-stack-fill.returning {
    background: linear-gradient(90deg, #34f5b0, #10b981);
}

.customer-stack-fill.new {
    background: linear-gradient(90deg, #60a5fa, #2563eb);
}

.customer-grid {
    display: grid;
    gap: 12px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.customer-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(52, 245, 176, 0.08);
    border-radius: 12px;
    padding: 14px;
}

.customer-card span {
    color: #86efac;
    display: block;
    font-size: 0.74rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 6px;
    text-transform: uppercase;
}

.customer-card strong {
    color: #f8fafc;
    display: block;
    font-size: 1.35rem;
}

.customer-card em {
    color: #cbd5e1;
    display: block;
    font-size: 0.78rem;
    font-style: normal;
    margin-top: 4px;
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
    background: linear-gradient(135deg, #3b82f6, #2563eb);
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
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
            <button class="export-btn" onclick="exportToCSV()">📥 Export CSV</button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">💰 Total Revenue</div>
            <div class="kpi-value">₹<span id="totalRevenue"><?php echo e(number_format($kpis['totalRevenue'], 2)); ?></span></div>
            <div class="kpi-growth <?php echo e($kpis['revenueGrowth'] >= 0 ? '' : 'negative'); ?>">
                <?php echo e($kpis['revenueGrowth'] >= 0 ? '↑' : '↓'); ?> <?php echo e(abs($kpis['revenueGrowth'])); ?>% vs last month
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">📦 Total Orders</div>
            <div class="kpi-value"><?php echo e($kpis['totalOrders']); ?></div>
            <div class="kpi-growth">This period</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">🛒 Avg Order Value</div>
            <div class="kpi-value">₹<?php echo e(number_format($kpis['averageOrderValue'], 2)); ?></div>
            <div class="kpi-growth">Per transaction</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">📈 Growth Rate</div>
            <div class="kpi-value <?php echo e($kpis['revenueGrowth'] >= 0 ? '' : 'negative'); ?>"><?php echo e($kpis['revenueGrowth']); ?>%</div>
            <div class="kpi-growth">Month-on-month</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Last 7 Days Sales -->
        <div class="chart-card full-width-chart sales-pulse-card">
            <div class="chart-title">
                <div class="chart-icon">📅</div>
                Last 7 Days Sales Movement
                <small>Always shows the latest 7 days, independent of date filters</small>
            </div>
            <div class="chart-container sales-pulse-container">
                <?php
                    $chartWidth = 760;
                    $chartHeight = 320;
                    $leftPad = 34;
                    $rightPad = 16;
                    $topPad = 20;
                    $bottomPad = 26;
                    $plotWidth = $chartWidth - $leftPad - $rightPad;
                    $plotHeight = $chartHeight - $topPad - $bottomPad;
                    $baseY = $topPad + $plotHeight;
                    $maxRevenue = max(collect($sevenDaySales)->max('revenue') ?? 0, 1);
                    $pointGap = count($sevenDaySales) > 1 ? $plotWidth / (count($sevenDaySales) - 1) : 0;
                    $chartPoints = [];

                    foreach ($sevenDaySales as $index => $saleDay) {
                        $x = $leftPad + ($pointGap * $index);
                        $y = $baseY - (($saleDay['revenue'] / $maxRevenue) * $plotHeight);
                        $chartPoints[] = [
                            'x' => round($x, 2),
                            'y' => round($y, 2),
                            'day' => $saleDay['day'],
                            'date' => $saleDay['date'],
                            'revenue' => $saleDay['revenue'],
                        ];
                    }

                    $linePoints = collect($chartPoints)
                        ->map(fn ($point) => $point['x'] . ',' . $point['y'])
                        ->implode(' ');

                    $areaPoints = $leftPad . ',' . $baseY . ' ' . $linePoints . ' ' . last($chartPoints)['x'] . ',' . $baseY;
                    $yMarkers = [0.25, 0.5, 0.75, 1];
                ?>
                <div class="sales-pulse-visual">
                    <svg class="sales-pulse-svg" viewBox="0 0 <?php echo e($chartWidth); ?> <?php echo e($chartHeight); ?>" preserveAspectRatio="none" role="img" aria-label="Last 7 days sales chart">
                        <defs>
                            <pattern id="salesGridSmall" width="18" height="18" patternUnits="userSpaceOnUse">
                                <path d="M 18 0 L 0 0 0 18" fill="none" stroke="rgba(52,245,176,0.05)" stroke-width="1"></path>
                            </pattern>
                            <pattern id="salesGridLarge" width="72" height="72" patternUnits="userSpaceOnUse">
                                <rect width="72" height="72" fill="url(#salesGridSmall)"></rect>
                                <path d="M 72 0 L 0 0 0 72" fill="none" stroke="rgba(52,245,176,0.09)" stroke-width="1.2"></path>
                            </pattern>
                            <linearGradient id="salesAreaFill" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#34f5b0" stop-opacity="0.42"></stop>
                                <stop offset="55%" stop-color="#34f5b0" stop-opacity="0.16"></stop>
                                <stop offset="100%" stop-color="#34f5b0" stop-opacity="0.02"></stop>
                            </linearGradient>
                            <filter id="salesLineGlow" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="0" stdDeviation="6" flood-color="#34f5b0" flood-opacity="0.65"></feDropShadow>
                            </filter>
                        </defs>

                        <rect x="<?php echo e($leftPad); ?>" y="<?php echo e($topPad); ?>" width="<?php echo e($plotWidth); ?>" height="<?php echo e($plotHeight); ?>" fill="url(#salesGridLarge)" rx="10"></rect>

                        <?php $__currentLoopData = $yMarkers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $marker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $gridY = round($baseY - ($plotHeight * $marker), 2);
                            ?>
                            <line x1="<?php echo e($leftPad); ?>" y1="<?php echo e($gridY); ?>" x2="<?php echo e($chartWidth - $rightPad); ?>" y2="<?php echo e($gridY); ?>" stroke="rgba(52,245,176,0.10)" stroke-width="1"></line>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <line x1="<?php echo e($leftPad); ?>" y1="<?php echo e($baseY); ?>" x2="<?php echo e($chartWidth - $rightPad); ?>" y2="<?php echo e($baseY); ?>" stroke="rgba(203,213,225,0.55)" stroke-width="2"></line>

                        <polygon points="<?php echo e($areaPoints); ?>" fill="url(#salesAreaFill)"></polygon>
                        <polyline points="<?php echo e($linePoints); ?>" fill="none" stroke="#34f5b0" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" filter="url(#salesLineGlow)"></polyline>

                        <?php $__currentLoopData = $chartPoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $isLastPoint = $index === count($chartPoints) - 1;
                            ?>
                            <circle cx="<?php echo e($point['x']); ?>" cy="<?php echo e($point['y']); ?>" r="<?php echo e($isLastPoint ? 5.5 : 3.2); ?>" fill="<?php echo e($isLastPoint ? '#d1fae5' : '#34f5b0'); ?>" stroke="#34f5b0" stroke-width="<?php echo e($isLastPoint ? 2.5 : 0); ?>"></circle>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </svg>
                </div>

                <div class="sales-pulse-axis-labels">
                    <?php $__currentLoopData = $sevenDaySales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saleDay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="sales-pulse-axis-item">
                            <span class="sales-pulse-axis-day"><?php echo e($saleDay['day']); ?></span>
                            <span class="sales-pulse-axis-value">₹<?php echo e(number_format($saleDay['revenue'], 0)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="chart-card neon-chart-card">
            <div class="chart-title">
                <div class="chart-icon">🏆</div>
                Top Selling Products
                <?php
                    $topProductLeader = $topProducts[0] ?? null;
                    $maxProductRevenue = max(collect($topProducts)->max('revenue') ?? 0, 1);
                ?>
                <small>
                    <?php if($topProductLeader): ?>
                        Leader: <?php echo e(\Illuminate\Support\Str::limit($topProductLeader['name'], 30)); ?> • <?php echo e($topProductLeader['quantity']); ?> units • ₹<?php echo e(number_format($topProductLeader['revenue'], 0)); ?>

                    <?php else: ?>
                        No product sales tracked yet
                    <?php endif; ?>
                </small>
            </div>
            <div class="chart-container">
                <canvas id="topProductsChart"></canvas>
                <div class="neon-chart-body">
                    <div class="metric-list">
                        <?php $__empty_1 = true; $__currentLoopData = array_slice($topProducts, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $productBarWidth = max(8, ($product['revenue'] / $maxProductRevenue) * 100);
                                $productImage = !empty($product['image']) ? asset($product['image']) : null;
                            ?>
                            <div class="metric-row">
                                <div class="metric-row-top">
                                    <div class="metric-product">
                                        <div class="metric-thumb <?php echo e($productImage ? '' : 'no-image'); ?>">
                                            <?php if($productImage): ?>
                                                <img src="<?php echo e($productImage); ?>" alt="<?php echo e($product['name']); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <?php endif; ?>
                                            <span class="metric-thumb-fallback">IMG</span>
                                        </div>
                                        <div class="metric-text">
                                            <div class="metric-name"><?php echo e(\Illuminate\Support\Str::limit($product['name'], 34)); ?></div>
                                            <div class="metric-sub"><?php echo e($product['quantity']); ?> units sold</div>
                                        </div>
                                    </div>
                                    <div class="metric-value">₹<?php echo e(number_format($product['revenue'], 0)); ?></div>
                                </div>
                                <div class="metric-track">
                                    <div class="metric-fill" style="width: <?php echo e(min(100, $productBarWidth)); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="metric-row">
                                <div class="metric-name">No product sales data available for this period.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Categories -->
        <div class="chart-card neon-chart-card">
            <div class="chart-title">
                <div class="chart-icon">🎯</div>
                Category Distribution
                <?php
                    $sortedCategories = collect($topCategories)->sortByDesc('revenue')->values();
                    $topCategoryLeader = $sortedCategories->first();
                    $categoryRevenueTotal = max($sortedCategories->sum('revenue'), 1);
                ?>
                <small>
                    <?php if($topCategoryLeader): ?>
                        Top share: <?php echo e($topCategoryLeader['name']); ?> • <?php echo e(number_format(($topCategoryLeader['revenue'] / $categoryRevenueTotal) * 100, 1)); ?>% of tracked revenue
                    <?php else: ?>
                        No category distribution available yet
                    <?php endif; ?>
                </small>
            </div>
            <div class="chart-container">
                <canvas id="topCategoriesChart"></canvas>
                <div class="neon-chart-body">
                    <div class="metric-list">
                        <?php $__empty_1 = true; $__currentLoopData = $sortedCategories->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $categoryPercent = ($category['revenue'] / $categoryRevenueTotal) * 100;
                            ?>
                            <div class="metric-row">
                                <div class="metric-row-top">
                                    <div>
                                        <div class="metric-name"><?php echo e($category['name']); ?></div>
                                        <div class="metric-sub"><?php echo e(number_format($categoryPercent, 1)); ?>% share • <?php echo e($category['quantity']); ?> items</div>
                                    </div>
                                    <div class="metric-value">₹<?php echo e(number_format($category['revenue'], 0)); ?></div>
                                </div>
                                <div class="metric-track">
                                    <div class="metric-fill" style="width: <?php echo e(min(100, max(8, $categoryPercent))); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="metric-row">
                                <div class="metric-name">No category sales data available for this period.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Sales -->
        <div class="chart-card neon-chart-card">
            <div class="chart-title">
                <div class="chart-icon">📊</div>
                Monthly Sales Trend
                <?php
                    $monthWidth = 620;
                    $monthHeight = 230;
                    $monthLeftPad = 24;
                    $monthRightPad = 10;
                    $monthTopPad = 16;
                    $monthBottomPad = 24;
                    $monthPlotWidth = $monthWidth - $monthLeftPad - $monthRightPad;
                    $monthPlotHeight = $monthHeight - $monthTopPad - $monthBottomPad;
                    $monthBaseY = $monthTopPad + $monthPlotHeight;
                    $monthMaxRevenue = max(collect($monthlySales)->max('revenue') ?? 0, 1);
                    $monthGap = count($monthlySales) > 1 ? $monthPlotWidth / (count($monthlySales) - 1) : 0;
                    $monthPoints = [];

                    foreach ($monthlySales as $index => $saleDay) {
                        $monthX = $monthLeftPad + ($monthGap * $index);
                        $monthY = $monthBaseY - (($saleDay['revenue'] / $monthMaxRevenue) * $monthPlotHeight);
                        $monthPoints[] = ['x' => round($monthX, 2), 'y' => round($monthY, 2)];
                    }

                    $monthLinePoints = collect($monthPoints)->map(fn ($point) => $point['x'] . ',' . $point['y'])->implode(' ');
                    $monthAreaPoints = $monthLeftPad . ',' . $monthBaseY . ' ' . $monthLinePoints . ' ' . last($monthPoints)['x'] . ',' . $monthBaseY;
                    $peakMonthDay = collect($monthlySales)->sortByDesc('revenue')->first();
                    $latestMonthDay = collect($monthlySales)->last();
                    $averageMonthRevenue = collect($monthlySales)->avg('revenue');
                ?>
                <small>
                    Peak day: <?php echo e($peakMonthDay['date'] ?? 'N/A'); ?> • ₹<?php echo e(number_format($peakMonthDay['revenue'] ?? 0, 0)); ?>

                </small>
            </div>
            <div class="chart-container">
                <canvas id="monthlySalesChart"></canvas>
                <div class="neon-chart-body">
                    <div class="mini-chart-surface">
                        <svg class="mini-chart-svg" viewBox="0 0 <?php echo e($monthWidth); ?> <?php echo e($monthHeight); ?>" preserveAspectRatio="none" role="img" aria-label="Monthly sales trend chart">
                            <defs>
                                <pattern id="monthGrid" width="22" height="22" patternUnits="userSpaceOnUse">
                                    <path d="M 22 0 L 0 0 0 22" fill="none" stroke="rgba(52,245,176,0.05)" stroke-width="1"></path>
                                </pattern>
                                <linearGradient id="monthAreaFill" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#34f5b0" stop-opacity="0.35"></stop>
                                    <stop offset="100%" stop-color="#34f5b0" stop-opacity="0.02"></stop>
                                </linearGradient>
                            </defs>
                            <rect x="<?php echo e($monthLeftPad); ?>" y="<?php echo e($monthTopPad); ?>" width="<?php echo e($monthPlotWidth); ?>" height="<?php echo e($monthPlotHeight); ?>" fill="url(#monthGrid)" rx="10"></rect>
                            <line x1="<?php echo e($monthLeftPad); ?>" y1="<?php echo e($monthBaseY); ?>" x2="<?php echo e($monthWidth - $monthRightPad); ?>" y2="<?php echo e($monthBaseY); ?>" stroke="rgba(203,213,225,0.45)" stroke-width="2"></line>
                            <polygon points="<?php echo e($monthAreaPoints); ?>" fill="url(#monthAreaFill)"></polygon>
                            <polyline points="<?php echo e($monthLinePoints); ?>" fill="none" stroke="#34f5b0" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></polyline>
                        </svg>
                    </div>
                    <div class="chart-summary-grid">
                        <div class="chart-summary-chip">
                            <span>Peak</span>
                            <strong><?php echo e($peakMonthDay['date'] ?? 'N/A'); ?></strong>
                            <em>₹<?php echo e(number_format($peakMonthDay['revenue'] ?? 0, 0)); ?></em>
                        </div>
                        <div class="chart-summary-chip">
                            <span>Latest</span>
                            <strong><?php echo e($latestMonthDay['date'] ?? 'N/A'); ?></strong>
                            <em>₹<?php echo e(number_format($latestMonthDay['revenue'] ?? 0, 0)); ?></em>
                        </div>
                        <div class="chart-summary-chip">
                            <span>Average</span>
                            <strong>Month Pace</strong>
                            <em>₹<?php echo e(number_format($averageMonthRevenue ?? 0, 0)); ?></em>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yearly Sales -->
        <div class="chart-card neon-chart-card">
            <div class="chart-title">
                <div class="chart-icon">📈</div>
                Yearly Revenue
                <?php
                    $bestYearMonth = collect($yearlySales)->sortByDesc('revenue')->first();
                    $maxYearRevenue = max(collect($yearlySales)->max('revenue') ?? 0, 1);
                ?>
                <small>
                    Best month: <?php echo e($bestYearMonth['month'] ?? 'N/A'); ?> • ₹<?php echo e(number_format($bestYearMonth['revenue'] ?? 0, 0)); ?>

                </small>
            </div>
            <div class="chart-container">
                <canvas id="yearlySalesChart"></canvas>
                <div class="neon-chart-body">
                    <div class="year-bars">
                        <?php $__empty_1 = true; $__currentLoopData = $yearlySales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yearSale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $yearBarHeight = $yearSale['revenue'] > 0
                                    ? max(6, ($yearSale['revenue'] / $maxYearRevenue) * 100)
                                    : 0;
                            ?>
                            <div class="year-bar-item">
                                <div class="year-bar-track">
                                    <div class="year-bar-fill" style="height: <?php echo e(min(100, $yearBarHeight)); ?>%"></div>
                                </div>
                                <div class="year-bar-label"><?php echo e($yearSale['month']); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="metric-row">
                                <div class="metric-name">No yearly revenue data available yet.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue vs Orders -->
        <div class="chart-card neon-chart-card">
            <div class="chart-title">
                <div class="chart-icon">💹</div>
                Revenue vs Orders
                <?php
                    $revenueVsOrdersTotalRevenue = collect($revenueVsOrders)->sum('revenue');
                    $revenueVsOrdersTotalOrders = collect($revenueVsOrders)->sum('orders');
                    $maxRevenueComparison = max(collect($revenueVsOrders)->max('revenue') ?? 0, 1);
                    $maxOrderComparison = max(collect($revenueVsOrders)->max('orders') ?? 0, 1);
                ?>
                <small>
                    Last 7 points • ₹<?php echo e(number_format($revenueVsOrdersTotalRevenue, 0)); ?> revenue • <?php echo e($revenueVsOrdersTotalOrders); ?> orders
                </small>
            </div>
            <div class="chart-container">
                <canvas id="revenueVsOrdersChart"></canvas>
                <div class="neon-chart-body">
                    <div class="compare-grid">
                        <?php $__empty_1 = true; $__currentLoopData = $revenueVsOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comparisonDay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $revenueBarHeight = $comparisonDay['revenue'] > 0
                                    ? max(8, ($comparisonDay['revenue'] / $maxRevenueComparison) * 100)
                                    : 0;
                                $orderBarHeight = $comparisonDay['orders'] > 0
                                    ? max(8, ($comparisonDay['orders'] / $maxOrderComparison) * 100)
                                    : 0;
                            ?>
                            <div class="compare-group">
                                <div class="compare-bars">
                                    <div class="compare-bar revenue" style="height: <?php echo e(min(100, $revenueBarHeight)); ?>%"></div>
                                    <div class="compare-bar orders" style="height: <?php echo e(min(100, $orderBarHeight)); ?>%"></div>
                                </div>
                                <div class="compare-label"><?php echo e($comparisonDay['date']); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="metric-row">
                                <div class="metric-name">No revenue or order comparisons available for this range.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="compare-legend">
                        <span><i class="legend-revenue"></i>Revenue</span>
                        <span><i class="legend-orders"></i>Orders</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Analytics -->
        <div class="chart-card neon-chart-card">
            <div class="chart-title">
                <div class="chart-icon">👥</div>
                Customer Breakdown
                <?php
                    $customerTotal = max($customerAnalytics['total'] ?? 0, 1);
                    $returningPercent = (($customerAnalytics['returning'] ?? 0) / $customerTotal) * 100;
                    $newPercent = (($customerAnalytics['new'] ?? 0) / $customerTotal) * 100;
                    $dominantCustomerType = ($customerAnalytics['returning'] ?? 0) >= ($customerAnalytics['new'] ?? 0) ? 'Returning customers lead' : 'New customers lead';
                ?>
                <small>
                    <?php echo e($dominantCustomerType); ?> • <?php echo e(number_format(max($returningPercent, $newPercent), 1)); ?>% share
                </small>
            </div>
            <div class="chart-container">
                <canvas id="customerAnalyticsChart"></canvas>
                <div class="neon-chart-body">
                    <div class="customer-stack" aria-label="Customer split">
                        <div class="customer-stack-fill returning" style="width: <?php echo e($returningPercent); ?>%"></div>
                        <div class="customer-stack-fill new" style="width: <?php echo e($newPercent); ?>%"></div>
                    </div>
                    <div class="customer-grid">
                        <div class="customer-card">
                            <span>Total</span>
                            <strong><?php echo e($customerAnalytics['total'] ?? 0); ?></strong>
                            <em>Tracked customers in selected range</em>
                        </div>
                        <div class="customer-card">
                            <span>Returning</span>
                            <strong><?php echo e($customerAnalytics['returning'] ?? 0); ?></strong>
                            <em><?php echo e(number_format($returningPercent, 1)); ?>% of total customers</em>
                        </div>
                        <div class="customer-card">
                            <span>New</span>
                            <strong><?php echo e($customerAnalytics['new'] ?? 0); ?></strong>
                            <em><?php echo e(number_format($newPercent, 1)); ?>% of total customers</em>
                        </div>
                    </div>
                </div>
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
                    <?php $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($product['name']); ?></td>
                        <td><?php echo e($product['stock']); ?> units</td>
                        <td>₹<?php echo e(number_format($product['price'], 2)); ?></td>
                        <td>
                            <span class="product-badge <?php echo e($product['stock'] <= 5 ? 'status-critical' : 'status-warning'); ?>">
                                <?php echo e($product['stock'] <= 5 ? '🔴 Critical' : '🟡 Warning'); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
if (false) {
const analyticsData = {
    topProducts: <?php echo json_encode($topProducts, 15, 512) ?>,
    topCategories: <?php echo json_encode($topCategories, 15, 512) ?>,
    sevenDaySales: <?php echo json_encode($sevenDaySales, 15, 512) ?>,
    monthlySales: <?php echo json_encode($monthlySales, 15, 512) ?>,
    yearlySales: <?php echo json_encode($yearlySales, 15, 512) ?>,
    revenueVsOrders: <?php echo json_encode($revenueVsOrders, 15, 512) ?>,
    customerAnalytics: <?php echo json_encode($customerAnalytics, 15, 512) ?>,
};

// Color schemes
const colors = {
    primary: 'rgba(59, 130, 246, 0.8)',
    primaryLight: 'rgba(59, 130, 246, 0.2)',
    secondary: 'rgba(139, 92, 246, 0.8)',
    secondaryLight: 'rgba(139, 92, 246, 0.2)',
    danger: 'rgba(239, 68, 68, 0.8)',
    dangerLight: 'rgba(239, 68, 68, 0.2)',
    warning: 'rgba(245, 158, 11, 0.8)',
    success: 'rgba(59, 130, 246, 0.8)',
    neon: '#34f5b0',
    neonSoft: 'rgba(52, 245, 176, 0.18)',
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

const salesGlowPlugin = {
    id: 'salesGlowPlugin',
    beforeDatasetDraw(chart, args) {
        if (args.index !== 0) {
            return;
        }

        const { ctx } = chart;
        ctx.save();
        ctx.shadowColor = 'rgba(52, 245, 176, 0.65)';
        ctx.shadowBlur = 18;
    },
    afterDatasetDraw(chart, args) {
        if (args.index !== 0) {
            return;
        }

        chart.ctx.restore();
    }
};

const sevenDaySalesCanvas = document.getElementById('sevenDaySalesChart');

if (sevenDaySalesCanvas && sevenDaySalesCanvas.getContext) {
const sevenDaySalesCtx = sevenDaySalesCanvas.getContext('2d');
const sevenDaySalesGradient = sevenDaySalesCtx.createLinearGradient(0, 0, 0, 380);
sevenDaySalesGradient.addColorStop(0, 'rgba(52, 245, 176, 0.45)');
sevenDaySalesGradient.addColorStop(0.55, 'rgba(52, 245, 176, 0.16)');
sevenDaySalesGradient.addColorStop(1, 'rgba(52, 245, 176, 0.02)');

// Last 7 Days Sales Chart
new Chart(sevenDaySalesCtx, {
    type: 'line',
    data: {
        labels: analyticsData.sevenDaySales.map(d => d.date),
        datasets: [{
            label: 'Sales (₹)',
            data: analyticsData.sevenDaySales.map(d => d.revenue),
            borderColor: colors.neon,
            backgroundColor: sevenDaySalesGradient,
            borderWidth: 3,
            fill: true,
            tension: 0.35,
            pointRadius: analyticsData.sevenDaySales.map((_, index, items) => index === items.length - 1 ? 4 : 0),
            pointHoverRadius: 6,
            pointBorderWidth: analyticsData.sevenDaySales.map((_, index, items) => index === items.length - 1 ? 2 : 0),
            pointBackgroundColor: analyticsData.sevenDaySales.map((_, index, items) => index === items.length - 1 ? '#d1fae5' : colors.neon),
            pointBorderColor: analyticsData.sevenDaySales.map((_, index, items) => index === items.length - 1 ? colors.neon : 'transparent'),
        }]
    },
    options: {
        ...chartOptions,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(3, 7, 12, 0.94)',
                borderColor: 'rgba(52, 245, 176, 0.25)',
                borderWidth: 1,
                callbacks: {
                    title: items => analyticsData.sevenDaySales[items[0].dataIndex]?.day + ' • ' + items[0].label,
                    label: context => ' Sales: ₹' + Number(context.raw).toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }),
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: 'rgba(197, 255, 229, 0.92)',
                    font: { size: 11, weight: '600' }
                },
                grid: {
                    color: 'rgba(52, 245, 176, 0.08)',
                    drawBorder: false
                },
                border: {
                    color: 'rgba(148, 163, 184, 0.35)'
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: 'rgba(197, 255, 229, 0.86)',
                    callback: value => '₹' + value
                },
                grid: {
                    color: 'rgba(52, 245, 176, 0.08)',
                    drawBorder: false
                },
                border: {
                    color: 'rgba(148, 163, 184, 0.35)'
                }
            }
        }
    },
    plugins: [salesGlowPlugin]
});
}

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

}

// Apply Filters
function applyFilters() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    if (dateFrom && dateTo) {
        window.location.href = `/admin/analytics?date_from=${dateFrom}&date_to=${dateTo}`;
    }
}

// Export to CSV
function exportToCSV() {
    const csvContent = generateCSVContent();
    downloadCSV(csvContent, 'sales-analytics-' + new Date().toISOString().split('T')[0] + '.csv');
}

function generateCSVContent() {
    // Get current date and time
    const now = new Date();
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    
    const dayName = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    const generatedDateTime = `${dayName}, ${date} ${month} ${year} at ${hours}:${minutes}:${seconds}`;
    
    let csv = [];
    
    // Header Section
    csv.push('SALES ANALYTICS REPORT');
    csv.push('Generated: ' + generatedDateTime);
    csv.push('');
    csv.push('');
    
    // KPI Section
    csv.push('KEY PERFORMANCE INDICATORS');
    csv.push('');
    csv.push('Metric,Value');
    
    const totalRevenue = document.getElementById('totalRevenue').textContent.trim();
    csv.push('Total Revenue,' + totalRevenue);
    
    const totalOrders = document.querySelector('.kpi-card:nth-child(2) .kpi-value').textContent.trim();
    csv.push('Total Orders,' + totalOrders);
    
    const avgOrderValue = document.querySelector('.kpi-card:nth-child(3) .kpi-value').textContent.trim();
    csv.push('Average Order Value,' + avgOrderValue);
    
    const growthRate = document.querySelector('.kpi-card:nth-child(4) .kpi-value').textContent.trim();
    csv.push('Growth Rate,' + growthRate);
    
    csv.push('');
    csv.push('');
    
    // Last 7 Days Sales Section
    csv.push('LAST 7 DAYS SALES BREAKDOWN');
    csv.push('');
    csv.push('Day of Week,Revenue');
    
    const sevenDayItems = document.querySelectorAll('.sales-pulse-axis-item');
    sevenDayItems.forEach(item => {
        const day = item.querySelector('.sales-pulse-axis-day').textContent.trim();
        const revenue = item.querySelector('.sales-pulse-axis-value').textContent.trim();
        csv.push(day + ',' + revenue);
    });
    
    csv.push('');
    csv.push('');
    
    // Top Products Section
    csv.push('TOP 5 SELLING PRODUCTS');
    csv.push('');
    csv.push('Rank,Product Name,Units Sold,Revenue');
    
    const productRows = document.querySelectorAll('.metric-row');
    let productRank = 1;
    productRows.forEach((row) => {
        const nameElem = row.querySelector('.metric-name');
        const subElem = row.querySelector('.metric-sub');
        const valueElem = row.querySelector('.metric-value');
        
        if (nameElem && subElem && valueElem && nameElem.textContent.trim() !== 'No product sales data available for this period.') {
            const name = nameElem.textContent.trim();
            const units = subElem.textContent.replace(' units sold', '').trim();
            const revenue = valueElem.textContent.trim();
            
            csv.push(productRank + ',"' + name + '",' + units + ',' + revenue);
            productRank++;
            
            if (productRank > 5) return;
        }
    });
    
    csv.push('');
    csv.push('');
    
    // Low Stock Products Section
    csv.push('LOW STOCK PRODUCTS - HIGH PRIORITY');
    csv.push('');
    csv.push('Product Name,Current Stock,Price,Status');
    
    const tableRows = document.querySelectorAll('.products-table tbody tr');
    tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 4) {
            const name = cells[0].textContent.trim();
            const stock = cells[1].textContent.trim();
            const price = cells[2].textContent.trim();
            const status = cells[3].textContent.trim();
            
            csv.push('"' + name + '",' + stock + ',' + price + ',"' + status + '"');
        }
    });
    
    csv.push('');
    csv.push('');
    
    // Monthly Sales Summary Section
    csv.push('MONTHLY SALES SUMMARY');
    csv.push('');
    csv.push('Period,Revenue');
    
    const chartSummaryChips = document.querySelectorAll('.chart-summary-chip');
    if (chartSummaryChips.length > 0) {
        chartSummaryChips.forEach(chip => {
            const label = chip.querySelector('span').textContent.trim();
            const value = chip.querySelector('em').textContent.trim();
            csv.push(label + ',' + value);
        });
    }
    
    csv.push('');
    csv.push('');
    csv.push('--- End of Report ---');
    
    return csv.join('\n');
}

function downloadCSV(content, filename) {
    const element = document.createElement('a');
    element.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(content));
    element.setAttribute('download', filename);
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Veyronnnnnnnnnn\resources\views/admin/analytics/dashboard.blade.php ENDPATH**/ ?>