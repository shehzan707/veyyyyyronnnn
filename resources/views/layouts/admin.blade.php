<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard — VEYRON')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { 
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            background: linear-gradient(135deg, #0f2027 0%, #2c5364 50%, #0f2027 100%) !important;
            min-height: 100vh;
        }
        
        html {
            background: linear-gradient(135deg, #0f2027 0%, #2c5364 50%, #0f2027 100%);
        }
        
        /* Header Styling - Modern Dark Grey */
        .header { 
            background: linear-gradient(90deg, #0f2027 0%, #2c5364 50%, #0f2027 100%);
            color: #ffffff; 
            padding: 1.2rem 2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }
        
        .header .logo { 
            display: flex; 
            align-items: center; 
            gap: 1rem; 
            font-size: 1.3rem; 
            font-weight: 800;
            letter-spacing: 0.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #d4e8ff;
        }
        
        .header .logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 8px rgba(0, 212, 255, 0.4));
        }
        
        .header .logo img { 
            height: 45px; 
            border-radius: 8px;
            border: 2px solid rgba(0, 212, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(0, 212, 255, 0.1);
            padding: 2px;
        }
        
        .header .logo img:hover {
            border-color: rgba(0, 212, 255, 0.8);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.4);
            transform: scale(1.1) rotateY(5deg);
        }
        
        /* Navigation Styling */
        .nav { 
            display: flex; 
            gap: 0.5rem;
            align-items: center;
        }
        
        .nav a { 
            color: #b0b0b0;
            text-decoration: none; 
            padding: 0.8rem 1.4rem; 
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: inline-flex; 
            align-items: center; 
            gap: 0.6rem;
            font-weight: 600;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
        }
        
        .nav a::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1) 0%, rgba(0, 150, 200, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }
        
        .nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #00d4ff, #0099cc);
            transition: width 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .nav a:hover { 
            color: #00d4ff;
            background: rgba(255,255,255,0.08);
            border-color: rgba(0, 212, 255, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 212, 255, 0.2), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        
        .nav a:hover::before {
            opacity: 1;
        }
        
        .nav a:hover::after {
            width: 100%;
        }
        
        .nav a.active { 
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.2) 0%, rgba(0, 150, 200, 0.15) 100%);
            color: #00d4ff;
            border: 1px solid rgba(0, 212, 255, 0.5);
            box-shadow: 0 8px 24px rgba(0, 212, 255, 0.25), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        
        .nav a.active::after {
            width: 100%;
        }
        
        .nav a.active:hover {
            box-shadow: 0 12px 32px rgba(0, 212, 255, 0.4), inset 0 1px 0 rgba(255,255,255,0.1);
            transform: translateY(-4px);
            border-color: rgba(0, 212, 255, 0.8);
        }
        
        .nav a .material-icons {
            font-size: 1.2rem;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .nav a:hover .material-icons {
            transform: scale(1.25) rotate(5deg);
        }
        
        .nav a.active .material-icons {
            animation: iconGlow 0.6s ease infinite;
        }
        
        @keyframes iconGlow {
            0%, 100% { transform: scale(1) drop-shadow(0 0 0px rgba(0, 212, 255, 0.5)); }
            50% { transform: scale(1.15) drop-shadow(0 0 8px rgba(0, 212, 255, 0.8)); }
        }
        
        /* Container Styling */
        .container { 
            max-width: 100% !important;
            margin: 0 !important; 
            padding: 30px !important;
            background: linear-gradient(135deg, #0f2027 0%, #2c5364 50%, #0f2027 100%);
            min-height: calc(100vh - 100px);
        }
        
        /* Card Styling */
        .kpi-card, .chart-card, .form-card, .products-table, .categories-table {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 12px;
            color: #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .kpi-card:hover, .chart-card:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(52, 211, 153, 0.3) !important;
            transform: translateY(-3px);
        }
        
        /* Text Colors */
        h1, h2, h3, h4, h5, h6 {
            color: #fff !important;
        }
        
        p, span, label, .material-icons {
            color: #cbd5e1 !important;
        }
        
        /* Primary Button - Light Green */
        .btn-submit, .filter-btn, .export-btn, .action-btn.btn-blue, .action-btn.btn-edit,
        button[type="submit"]:not(.btn-delete), .btn-add, .categories-btn {
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%) !important;
            color: #fff !important;
            border: none !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            text-decoration: none !important;
        }
        
        .btn-submit:hover, .filter-btn:hover, .export-btn:hover, 
        .action-btn.btn-blue:hover, .action-btn.btn-edit:hover, button[type="submit"]:hover, 
        .btn-add:hover, .categories-btn:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: #fff !important;
        }
        
        /* Delete Button - Red */
        .action-btn.btn-delete, .btn-delete, .btn-red,
        button[type="submit"].btn-delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: #fff !important;
            border: none !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }
        
        .action-btn.btn-delete:hover, .btn-delete:hover, .btn-red:hover,
        button[type="submit"].btn-delete:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
        
        /* Form Elements */
        input, select, textarea {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #e2e8f0 !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            border-radius: 8px;
            padding: 10px;
            transition: all 0.3s ease;
        }
        
        input:focus, select:focus, textarea:focus {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(52, 211, 153, 0.5) !important;
            box-shadow: 0 0 12px rgba(52, 211, 153, 0.2);
            outline: none;
        }
        
        /* Tables */
        table {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #e2e8f0;
        }
        
        table th {
            background: rgba(52, 211, 153, 0.1) !important;
            color: #cbd5e1 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            font-weight: 600;
        }
        
        table td {
            border-color: rgba(255, 255, 255, 0.05) !important;
            color: #e2e8f0;
        }
        
        table tbody tr:hover {
            background: rgba(52, 211, 153, 0.1) !important;
        }
        
        /* Alert Messages */
        .alert-success {
            background: rgba(52, 211, 153, 0.2) !important;
            border: 1px solid rgba(52, 211, 153, 0.4) !important;
            color: #86efac !important;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.2) !important;
            border: 1px solid rgba(239, 68, 68, 0.4) !important;
            color: #fca5a5 !important;
        }
        
        /* Links */
        a {
            color: #34d399 !important;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        a:hover {
            color: #10b981 !important;
            text-decoration: underline;
        }
        
        /* Badge/Status Elements */
        .product-badge, .status-active {
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%) !important;
            color: #fff !important;
        }
        
        .status-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: #fff !important;
        }
        
        .status-critical {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: #fff !important;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="{{ asset('images/veyronlogo.jpg') }}" alt="VEYRON Logo">
            <span>VEYRON Admin</span>
        </div>
        <nav class="nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="material-icons">dashboard</span> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <span class="material-icons">inventory</span> Products
            </a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <span class="material-icons">shopping_cart</span> Orders
            </a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="material-icons">people</span> Users
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <span class="material-icons">category</span> Categories
            </a>
            <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
                <span class="material-icons">analytics</span> Analytics
            </a>
            <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <span class="material-icons">image</span> Banners
            </a>
            <a href="{{ route('admin.logout') }}">
                <span class="material-icons">logout</span> Logout
            </a>
        </nav>
    </header>

    <div class="container">
        @if(session('success'))
            <div style="background:#d4edda; color:#155724; padding:12px; border-radius:8px; margin-bottom:1rem;">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div style="background:#f8d7da; color:#721c24; padding:12px; border-radius:8px; margin-bottom:1rem;">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
