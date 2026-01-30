# Admin Sales Analytics Dashboard - Documentation

## Overview
A premium, enterprise-level analytics dashboard for the VEYRON e-commerce platform, providing comprehensive sales performance insights and business metrics.

## Files Created/Modified

### Backend
1. **[app/Http/Controllers/Admin/AnalyticsController.php](app/Http/Controllers/Admin/AnalyticsController.php)**
   - Main controller handling all analytics data generation
   - Methods:
     - `dashboard()` - Renders main analytics view
     - `getAnalyticsData()` - API endpoint for dynamic data fetching
     - `getKPIs()` - Key Performance Indicators (Revenue, Orders, AOV, Growth)
     - `getTopProducts()` - Top 8 selling products with quantity and revenue
     - `getTopCategories()` - Category sales distribution
     - `getDailySales()` - Hourly breakdown (Today vs Yesterday)
     - `getMonthlySales()` - Daily sales for current month
     - `getYearlySales()` - Monthly revenue for the year
     - `getRevenueVsOrders()` - Dual-axis comparison chart
     - `getCustomerAnalytics()` - Returning vs New customers
     - `getLowStockProducts()` - Products with stock ≤ 10 units

### Frontend
2. **[resources/views/admin/analytics/dashboard.blade.php](resources/views/admin/analytics/dashboard.blade.php)**
   - Modern glassmorphism UI with dark theme
   - Responsive layout (desktop, tablet, mobile)
   - 8 interactive charts using Chart.js v4.4.0
   - 4 KPI cards with growth indicators
   - Low-stock warning table
   - Date range filters
   - PDF export functionality

### Routes
3. **[routes/web.php](routes/web.php)**
   - `GET /admin/analytics` - Main dashboard page
   - `GET /admin/api/analytics` - API endpoint for data

### Layout
4. **[resources/views/layouts/admin.blade.php](resources/views/layouts/admin.blade.php)**
   - Added "Analytics" navigation link in admin sidebar
   - Positioned between Categories and Logout

---

## Features

### 📊 KPI Cards
- **Total Revenue**: Displays total sales with month-over-month growth percentage
- **Total Orders**: Number of orders in selected period
- **Average Order Value**: Revenue per transaction
- **Growth Rate**: Percentage change vs previous period

### 📈 Charts & Visualizations

1. **Daily Sales Chart** (Line Chart)
   - Today vs Yesterday comparison
   - Hourly breakdown (0-23 hours)
   - Dual-line visualization

2. **Top Selling Products** (Horizontal Bar Chart)
   - Top 8 products by revenue
   - Shows product name and revenue amount
   - Color-coded bars

3. **Category Distribution** (Doughnut Chart)
   - Sales contribution by category
   - Percentage breakdown
   - Interactive legend

4. **Monthly Sales Trend** (Area Chart)
   - Daily sales for current month
   - Smooth curve with area fill
   - Highlights peak days

5. **Yearly Revenue** (Bar Chart)
   - Monthly revenue across the year
   - Shows YoY trends
   - Color-coded monthly bars

6. **Revenue vs Orders** (Dual-Axis Bar Chart)
   - Revenue on left axis (₹)
   - Orders on right axis (count)
   - 7-day comparison

7. **Customer Breakdown** (Pie Chart)
   - Returning customers
   - New customers
   - Percentage split

8. **Low-Stock Products Table**
   - Products with stock ≤ 10 units
   - Color-coded status badges
   - Critical (≤5): Red
   - Warning (6-10): Orange

### 🎨 UI/UX Features
- **Glassmorphism Design**: Blur effect with transparency
- **Smooth Animations**: Slide-in animations for all cards
- **Hover Effects**: Interactive card elevation and color transitions
- **Responsive Grid**: Auto-fits to screen size
- **Dark Theme**: Easy on the eyes for extended use
- **Real-time KPIs**: Live growth percentage indicators

### 🛠️ Controls & Filters
- **Date Range Picker**: Select custom date range
- **Apply Button**: Refresh data with selected dates
- **Export to PDF**: Download dashboard as PDF file
- **Responsive Design**: Mobile-optimized layout

---

## Data Structure

### KPI Response
```json
{
  "totalRevenue": 45600.50,
  "totalOrders": 125,
  "averageOrderValue": 364.80,
  "revenueGrowth": 15.30
}
```

### Top Products
```json
[
  {
    "name": "Premium T-Shirt",
    "quantity": 156,
    "revenue": 4680.00,
    "image": "uploads/products/..."
  }
]
```

### Daily Sales
```json
{
  "today": [
    { "hour": "0:00", "revenue": 200.00 },
    { "hour": "1:00", "revenue": 150.00 }
  ],
  "yesterday": [
    { "hour": "0:00", "revenue": 180.00 },
    { "hour": "1:00", "revenue": 160.00 }
  ]
}
```

---

## Chart Libraries Used
- **Chart.js v4.4.0**: Lightweight charting library
- **html2pdf**: PDF export functionality

---

## Styling Details

### Color Scheme
- **Primary Blue**: `#3b82f6` (Charts & Interactive elements)
- **Secondary Teal**: `#0ab8b1` (Secondary data)
- **Warning Orange**: `#f59e0b` (Warning indicators)
- **Success Green**: `#10b981` (Positive metrics)
- **Danger Red**: `#ef4444` (Critical alerts)

### Typography
- Font: Segoe UI, Tahoma, Geneva, Verdana
- Dashboard Title: 2.5rem, Bold
- Section Title: 1.2rem, Bold
- KPI Label: 0.9rem, Uppercase

### Responsive Breakpoints
- **Desktop**: 1200px+ - Full grid layout
- **Tablet**: 768px - 1199px - Stack to single column
- **Mobile**: < 768px - Fully responsive

---

## Usage

### Accessing the Dashboard
1. Login to admin panel
2. Click "📊 Analytics" in the sidebar
3. View real-time sales metrics

### Filtering Data
1. Select start date in "Date From" field
2. Select end date in "Date To" field
3. Click "🔍 Apply" button
4. Dashboard updates with filtered data

### Exporting Analytics
1. Click "📥 Export PDF" button
2. PDF downloads with current dashboard state
3. All charts are rendered in landscape format

---

## Database Queries Optimized

### OrderItems Relationship
Uses efficient joins with the `orders` and `admin_products` tables to aggregate:
- Product sales quantities
- Revenue calculations
- Category-wise breakdowns

### Performance
- Lazy loading of charts
- Optimized aggregation queries
- Client-side rendering to reduce server load
- CDN-hosted Chart.js library

---

## API Integration

### Endpoint: `/admin/api/analytics`
- **Method**: GET
- **Parameters**: 
  - `date_from` (optional): Start date
  - `date_to` (optional): End date
- **Response**: JSON with all analytics data

### Usage Example
```javascript
fetch('/admin/api/analytics?date_from=2026-01-01&date_to=2026-01-31')
  .then(res => res.json())
  .then(data => console.log(data))
```

---

## Future Enhancements
- [ ] Real-time WebSocket updates
- [ ] Custom report builder
- [ ] Email scheduling for analytics
- [ ] Advanced forecasting with ML
- [ ] Competitor benchmarking
- [ ] Customer lifetime value (CLV) tracking
- [ ] Cohort analysis
- [ ] Inventory optimization suggestions

---

## Browser Compatibility
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

---

## File Locations Summary
```
app/
  Http/
    Controllers/
      Admin/
        AnalyticsController.php ✨ NEW

resources/
  views/
    admin/
      analytics/
        dashboard.blade.php ✨ NEW
    layouts/
      admin.blade.php (UPDATED - Added Analytics link)

routes/
  web.php (UPDATED - Added analytics routes)
```

---

Generated: January 24, 2026
Version: 1.0.0
