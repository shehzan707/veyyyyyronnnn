# 🗺️ Analytics Dashboard - Navigation & Access Guide

## 🌐 URL MAPPING

```
┌─────────────────────────────────────────────────────┐
│           VEYRON ADMIN PANEL NAVIGATION             │
└─────────────────────────────────────────────────────┘

Admin Dashboard (/) 
    │
    ├─ /admin/dashboard
    │   └─ Dashboard Controller
    │
    ├─ /admin/products
    │   ├─ /admin/products (list)
    │   ├─ /admin/products/create
    │   ├─ /admin/products/{id}/edit
    │   └─ /admin/products/{id}
    │
    ├─ /admin/orders
    │   ├─ /admin/orders (list)
    │   ├─ /admin/orders/{id}
    │   └─ /admin/orders/{id}/status
    │
    ├─ /admin/users
    │   ├─ /admin/users (list)
    │   └─ /admin/users/{id}
    │
    ├─ /admin/categories
    │   ├─ /admin/categories (list)
    │   ├─ /admin/categories/create (inline)
    │   ├─ /admin/categories/{id}/edit
    │   └─ /admin/categories/{id}
    │
    ├─ ⭐ /admin/analytics           ← NEW
    │   └─ /admin/api/analytics      ← NEW API
    │
    └─ /admin/logout
```

## 📱 SIDEBAR NAVIGATION

```
┌─────────────────────────────────────┐
│     VEYRON ADMIN PANEL              │
│                                     │
│ Logo: [VEYRON ADMIN]                │
│                                     │
├─ 📊 Dashboard                       │
├─ 📦 Products                        │
├─ 🛒 Orders                          │
├─ 👥 Users                           │
├─ 📁 Categories                      │
├─ 📈 Analytics ⭐ NEW               │
│                                     │
└─ 🚪 Logout                         │
```

## 🎯 ROUTE HIERARCHY

### Admin Routes (Protected by 'admin' middleware)

```
Route::prefix('admin')->middleware('admin')->group(function () {
    
    // Main Dashboard
    Route::get('/dashboard')
        → DashboardController@index
        → admin.dashboard
    
    // Products Management
    Route::get('/products')
        → ProductController@index
        → admin.products.index
    Route::get('/products/create')
        → ProductController@create
        → admin.products.create
    Route::post('/products')
        → ProductController@store
        → admin.products.store
    Route::get('/products/{id}/edit')
        → ProductController@edit
        → admin.products.edit
    Route::put('/products/{id}')
        → ProductController@update
        → admin.products.update
    Route::delete('/products/{id}')
        → ProductController@destroy
        → admin.products.destroy
    
    // Categories Management
    Route::get('/categories')
        → CategoryController@index
        → admin.categories.index
    Route::post('/categories')
        → CategoryController@store
        → admin.categories.store
    Route::get('/categories/{id}/edit')
        → CategoryController@edit
        → admin.categories.edit
    Route::put('/categories/{id}')
        → CategoryController@update
        → admin.categories.update
    Route::delete('/categories/{id}')
        → CategoryController@destroy
        → admin.categories.destroy
    
    // ⭐ ANALYTICS - NEW
    Route::get('/analytics')
        → AnalyticsController@dashboard
        → admin.analytics
    Route::get('/api/analytics')
        → AnalyticsController@getAnalyticsData
        → admin.analytics.data
    
    // Orders Management
    Route::get('/orders')
        → OrderController@index
        → admin.orders.index
    Route::get('/orders/{id}')
        → OrderController@show
        → admin.orders.show
    Route::post('/orders/{id}/status')
        → OrderController@updateStatus
        → admin.orders.status
    
    // Users Management
    Route::get('/users')
        → UserController@index
        → admin.users.index
    Route::delete('/users/{id}')
        → UserController@destroy
        → admin.users.destroy
    
    // Logout
    Route::get('/logout')
        → Return redirect to login
        → admin.logout
});
```

## 🔗 ACCESSING THE ANALYTICS DASHBOARD

### Method 1: Direct URL
```
http://yourapp.local/admin/analytics
```

### Method 2: Blade Link
```blade
<a href="{{ route('admin.analytics') }}">Analytics</a>
```

### Method 3: Redirect
```php
redirect()->route('admin.analytics')
```

### Method 4: Sidebar Click
```
Admin Panel → Click "📈 Analytics" in sidebar
```

## 🔌 API ENDPOINT

### Endpoint URL
```
GET /admin/api/analytics
Route Name: admin.analytics.data
```

### Request Parameters (Optional)
```php
GET /admin/api/analytics?date_from=2026-01-01&date_to=2026-01-31
GET /admin/api/analytics?date_from=2026-01-20  // Defaults to today
```

### Response Format
```json
{
    "kpis": { ... },
    "topProducts": [ ... ],
    "topCategories": [ ... ],
    "dailySales": { ... },
    "monthlySales": [ ... ],
    "yearlySales": [ ... ],
    "revenueVsOrders": [ ... ],
    "customerAnalytics": { ... },
    "lowStockProducts": [ ... ]
}
```

### JavaScript Example
```javascript
// Fetch with filters
fetch('/admin/api/analytics?date_from=2026-01-01&date_to=2026-01-31')
    .then(res => res.json())
    .then(data => {
        console.log('KPIs:', data.kpis);
        console.log('Top Products:', data.topProducts);
        // Use data to update charts
    })
    .catch(error => console.error('Error:', error));
```

## 🎨 UI INTERACTION FLOW

```
User Accesses /admin/analytics
        │
        ▼
Dashboard Loads
├─ KPI cards display with calculations
├─ 8 Charts render (Chart.js initialized)
├─ Low-stock table populated
└─ Ready for interaction

User Can:
├─ View default data (last 30 days)
├─ Set custom date range
│  └─ Click "Apply" → API call → Dashboard updates
├─ Hover over charts for tooltips
├─ Click legend items to toggle series
├─ Export to PDF
│  └─ Click "Export PDF" → html2pdf → Download
└─ Navigate to other admin sections
   └─ Click sidebar links → Route change
```

## 🔐 AUTHENTICATION FLOW

```
User Visits /admin/analytics
        │
        ▼
Admin Middleware Check
├─ Is user logged in?  ──NO──> Redirect to /login
├─ Is user admin?      ──NO──> Redirect to home
└─ User valid?         ──YES─> Continue to dashboard
                               │
                               ▼
                        Generate Analytics Data
                        │
                        ▼
                    Render Dashboard View
```

## 📊 DATA FLOW ARCHITECTURE

```
Browser Request
    │
    ▼
/admin/analytics (GET)
    │
    ├─ Middleware Check (Admin)
    │
    ▼
AnalyticsController@dashboard
    │
    ├─ Call: generateAnalyticsData()
    │   │
    │   ├─ Query: Orders table → getKPIs()
    │   ├─ Query: OrderItems + Products → getTopProducts()
    │   ├─ Query: OrderItems + Categories → getTopCategories()
    │   ├─ Query: Orders grouped by date → getDailySales()
    │   ├─ Query: Orders grouped by day → getMonthlySales()
    │   ├─ Query: Orders grouped by month → getYearlySales()
    │   ├─ Query: Orders grouped by date → getRevenueVsOrders()
    │   ├─ Query: Users + Orders → getCustomerAnalytics()
    │   └─ Query: Products where stock ≤ 10 → getLowStockProducts()
    │
    ▼
Return View with Data
    │
    ├─ Pass $kpis to Blade
    ├─ Pass $topProducts to Blade
    ├─ Pass $topCategories to Blade
    ├─ Pass $dailySales to Blade
    ├─ Pass $monthlySales to Blade
    ├─ Pass $yearlySales to Blade
    ├─ Pass $revenueVsOrders to Blade
    ├─ Pass $customerAnalytics to Blade
    └─ Pass $lowStockProducts to Blade
    │
    ▼
Blade Template (dashboard.blade.php)
    │
    ├─ Render HTML structure
    ├─ Output KPI values
    ├─ Populate chart data JSON
    ├─ Initialize Chart.js
    ├─ Attach event listeners
    └─ Return rendered HTML
    │
    ▼
Browser Receives HTML
    │
    ├─ Load CSS styles
    ├─ Parse Chart.js data
    ├─ Render all charts
    ├─ Bind filter events
    ├─ Bind export events
    └─ Display fully rendered dashboard
    │
    ▼
User Interacts with Dashboard
```

## 🎯 NAVIGATION EXAMPLES

### Example 1: View Analytics
```
1. User logs in as admin
2. Sidebar shows "📈 Analytics" link
3. User clicks on it
4. Route: GET /admin/analytics
5. Controller: AnalyticsController@dashboard
6. View: admin.analytics.dashboard
7. Data: Last 30 days, all products, all categories
```

### Example 2: Filter by Date Range
```
1. User on analytics dashboard
2. Selects date "2026-01-01" in "Date From"
3. Selects date "2026-01-24" in "Date To"
4. Clicks "🔍 Apply" button
5. JavaScript event triggers
6. API call: GET /admin/api/analytics?date_from=2026-01-01&date_to=2026-01-24
7. Controller: AnalyticsController@getAnalyticsData
8. Returns: JSON with filtered data
9. JavaScript updates charts
10. Dashboard displays new metrics
```

### Example 3: Export PDF
```
1. User viewing analytics dashboard
2. Clicks "📥 Export PDF" button
3. JavaScript uses html2pdf library
4. Captures .analytics-container
5. Generates PDF with:
   - All KPI cards
   - All 7 charts
   - Low-stock table
   - Landscape orientation
6. Browser downloads: sales-analytics-2026-01-24.pdf
```

## 🔄 ROUTE ACTIVE STATE DETECTION

```blade
<!-- In admin.blade.php -->
<a href="{{ route('admin.analytics') }}" 
   class="{{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
    <span class="material-icons">analytics</span> Analytics
</a>
```

The `admin.analytics*` pattern matches:
- `admin.analytics` (main dashboard)
- `admin.analytics.data` (API endpoint)

When user is on either route, link shows as "active" with:
- Active background color
- Highlighted text
- Underline animation
- Icon glow effect

## 📋 ROUTE NAMES & PURPOSES

| Route | Method | URL | Controller | Purpose |
|-------|--------|-----|-----------|---------|
| admin.analytics | GET | /admin/analytics | AnalyticsController@dashboard | View dashboard |
| admin.analytics.data | GET | /admin/api/analytics | AnalyticsController@getAnalyticsData | Fetch data via API |

## 🛡️ SECURITY CONSIDERATIONS

```
All Analytics Routes:
├─ Protected by 'admin' middleware
├─ Require authenticated admin session
├─ CSRF token on POST/PUT/DELETE
├─ Input validation on filters
├─ No sensitive user data exposed
└─ Only aggregated data returned
```

---

**Navigation Guide Version**: 1.0.0  
**Last Updated**: January 24, 2026  
**Status**: ✅ Complete
