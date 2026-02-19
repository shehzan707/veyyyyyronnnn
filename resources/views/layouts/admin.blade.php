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
            background: #2a2a2a !important;
            min-height: 100vh;
        }
        
        html {
            background: #2a2a2a;
        }
        
        /* Header Styling - Dark Grey */
        .header { 
            background: #1a1a1a;
            color: #ffffff; 
            padding: 1.2rem 2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(255,255,255,0.15);
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
            color: #ffffff;
        }
        
        .header .logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.4));
        }
        
        .header .logo img { 
            height: 45px; 
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.1);
            padding: 2px;
        }
        
        .header .logo img:hover {
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
            transform: scale(1.1) rotateY(5deg);
        }
        
        /* Navigation Styling */
        .nav { 
            display: flex; 
            gap: 0.5rem;
            align-items: center;
        }
        
        .nav a { 
            color: #e0e0e0;
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
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .nav a::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
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
            background: #ffffff;
            transition: width 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .nav a:hover { 
            color: #ffffff;
            background: rgba(255,255,255,0.1);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.2), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        
        .nav a:hover::before {
            opacity: 1;
        }
        
        .nav a:hover::after {
            width: 100%;
        }
        
        .nav a.active { 
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.2), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        
        .nav a.active::after {
            width: 100%;
        }
        
        .nav a.active:hover {
            box-shadow: 0 12px 32px rgba(255, 255, 255, 0.3), inset 0 1px 0 rgba(255,255,255,0.1);
            transform: translateY(-4px);
            border-color: rgba(255, 255, 255, 0.6);
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
            0%, 100% { transform: scale(1) drop-shadow(0 0 0px rgba(255, 255, 255, 0.5)); }
            50% { transform: scale(1.15) drop-shadow(0 0 8px rgba(255, 255, 255, 0.8)); }
        }
        
        /* Container Styling */
        .container { 
            max-width: 100% !important;
            margin: 0 !important; 
            padding: 30px !important;
            background: #2a2a2a;
            min-height: calc(100vh - 100px);
        }
        
        /* Card Styling */
        .kpi-card, .chart-card, .form-card, .products-table, .categories-table {
            background: #3a3a3a !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 12px;
            color: #ffffff;
            transition: all 0.3s ease;
        }
        
        .kpi-card:hover, .chart-card:hover {
            background: #424242 !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            transform: translateY(-3px);
        }
        
        /* Text Colors */
        h1, h2, h3, h4, h5, h6 {
            color: #ffffff !important;
        }
        
        p, span, label, .material-icons {
            color: #ffffff !important;
        }
        
        /* Primary Button - Black with White Text */
        .btn-submit, .filter-btn, .export-btn, .action-btn.btn-blue, .action-btn.btn-edit,
        button[type="submit"]:not(.btn-delete), .btn-add, .categories-btn {
            background: #000000 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            text-decoration: none !important;
        }
        
        .btn-submit:hover, .filter-btn:hover, .export-btn:hover, 
        .action-btn.btn-blue:hover, .action-btn.btn-edit:hover, button[type="submit"]:hover, 
        .btn-add:hover, .categories-btn:hover {
            background: #1a1a1a !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
        }
        
        /* Delete Button - Black with White Text */
        .action-btn.btn-delete, .btn-delete, .btn-red,
        button[type="submit"].btn-delete {
            background: #000000 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
        
        .action-btn.btn-delete:hover, .btn-delete:hover, .btn-red:hover,
        button[type="submit"].btn-delete:hover {
            background: #1a1a1a !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
        }
        
        /* Form Elements */
        input, select, textarea {
            background: #3a3a3a !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 8px;
            padding: 10px;
            transition: all 0.3s ease;
        }
        
        input:focus, select:focus, textarea:focus {
            background: #424242 !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.15);
            outline: none;
        }
        
        /* Tables */
        table {
            background: #3a3a3a !important;
            color: #ffffff;
        }
        
        table th {
            background: #2a2a2a !important;
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            font-weight: 600;
        }
        
        table td {
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff;
        }
        
        table tbody tr:hover {
            background: #424242 !important;
        }
        
        /* Alert Messages */
        .alert-success {
            background: #2a2a2a !important;
            border: 1px solid rgba(76, 175, 80, 0.4) !important;
            color: #a8e6a8 !important;
        }
        
        .alert-error {
            background: #2a2a2a !important;
            border: 1px solid rgba(244, 67, 54, 0.4) !important;
            color: #ef9a9a !important;
        }
        
        /* Links */
        a {
            color: #ffffff !important;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        a:hover {
            color: #e0e0e0 !important;
            text-decoration: underline;
        }
        
        /* Badge/Status Elements */
        .product-badge, .status-active {
            background: #000000 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }
        
        .status-warning {
            background: #000000 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }
        
        .status-critical {
            background: #000000 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="{{ asset('images/adminlogo.png') }}" alt="VEYRON Logo">
            <span>   pADMIN CONSOLE</span>
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
            <div style="background:#1a1a1a; color:#a8e6a8; padding:12px; border-radius:8px; margin-bottom:1rem; border: 1px solid rgba(168, 230, 168, 0.4);">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div style="background:#1a1a1a; color:#ef9a9a; padding:12px; border-radius:8px; margin-bottom:1rem; border: 1px solid rgba(239, 154, 154, 0.4);">
                {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
