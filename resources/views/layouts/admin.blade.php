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
        :root {
            /* Light Theme Colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f9f9f9;
            --bg-tertiary: #f0f0f0;
            --text-primary: #000000;
            --text-secondary: #333333;
            --text-tertiary: #666666;
            --border-color: #e0e0e0;
            --btn-bg: #333333;
            --btn-text: #000000;
            --btn-hover: #555555;
            --card-bg: #ffffff;
            --input-bg: #ffffff;
            --input-border: #cccccc;
            --input-text: #000000;
            --placeholder-color: #999999;
        }

        body.theme-light {
            --bg-primary: #ffffff;
            --bg-secondary: #f9f9f9;
            --bg-tertiary: #f0f0f0;
            --text-primary: #000000;
            --text-secondary: #333333;
            --text-tertiary: #666666;
            --border-color: #e0e0e0;
            --btn-bg: #333333;
            --btn-text: #000000;
            --btn-hover: #555555;
            --card-bg: #ffffff;
            --input-bg: #ffffff;
            --input-border: #cccccc;
            --input-text: #000000;
            --placeholder-color: #999999;
        }

        body.theme-dark {
            --bg-primary: #2a2a2a;
            --bg-secondary: #333333;
            --bg-tertiary: #3d3d3d;
            --text-primary: #ffffff;
            --text-secondary: #e0e0e0;
            --text-tertiary: #b0b0b0;
            --border-color: #444444;
            --btn-bg: #ffffff;
            --btn-text: #000000;
            --btn-hover: #e0e0e0;
            --card-bg: #323232;
            --input-bg: #1a1a1a;
            --input-border: #444444;
            --input-text: #ffffff;
            --placeholder-color: #888888;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        body { 
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            transition: background-color 250ms ease, color 250ms ease;
        }
        
        html {
            background: var(--bg-primary);
        }
        
        /* Header Styling */
        .header { 
            background: var(--bg-secondary);
            color: var(--text-primary); 
            padding: 1.2rem 2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 250ms ease, color 250ms ease, border-color 250ms ease, box-shadow 250ms ease;
        }
        
        .header .logo { 
            display: flex; 
            align-items: center; 
            gap: 1rem; 
            font-size: 1.3rem; 
            font-weight: 800;
            letter-spacing: 0.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--text-primary);
        }
        
        .header .logo:hover {
            transform: scale(1.05);
        }
        
        .header .logo img { 
            height: 45px; 
            border-radius: 8px;
            border: 2px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--bg-tertiary);
            padding: 2px;
        }
        
        .header .logo img:hover {
            border-color: var(--btn-hover);
            transform: scale(1.1);
        }
        
        /* Navigation Styling */
        .nav { 
            display: flex; 
            gap: 0.5rem;
            align-items: center;
        }
        
        .nav a { 
            color: var(--text-tertiary);
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
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
        }
        
        .nav a::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--bg-tertiary);
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
            background: var(--btn-bg);
            transition: width 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        .nav a:hover { 
            color: var(--text-primary);
            background: var(--btn-hover);
            border-color: var(--btn-bg);
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .theme-dark .nav a:hover {
            background: var(--bg-tertiary);
            border-color: var(--border-color);
        }
        
        .nav a:hover::before {
            opacity: 1;
        }
        
        .nav a:hover::after {
            width: 100%;
        }
        
        .nav a.active { 
            background: var(--btn-hover);
            color: var(--text-primary);
            border: 1px solid var(--btn-bg);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .theme-light .nav a.active {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            box-shadow: none;
        }

        .theme-dark .nav a.active {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            box-shadow: none;
        }
        
        .nav a.active::after {
            width: 100%;
        }
        
        .nav a.active:hover {
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            transform: translateY(-4px);
            border-color: var(--text-primary);
        }

        .theme-light .nav a.active:hover {
            box-shadow: none;
            transform: none;
            border-color: var(--border-color);
        }

        .theme-dark .nav a.active:hover {
            box-shadow: none;
            border-color: var(--border-color);
        }
        
        .nav a .material-icons {
            font-size: 1.2rem;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        }
        
        .nav a:hover .material-icons {
            transform: scale(1.25) rotate(5deg);
        }
        
        .nav a.active .material-icons {
            animation: iconGlow 0.6s ease infinite;
        }
        
        @keyframes iconGlow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        
        /* Container Styling */
        .container { 
            max-width: 100% !important;
            margin: 0 !important; 
            padding: 30px !important;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: calc(100vh - 100px);
            transition: background-color 250ms ease, color 250ms ease;
        }
        
        /* Card Styling */
        .kpi-card, .chart-card, .form-card, .products-table, .categories-table {
            background: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 12px;
            color: var(--text-primary);
            transition: all 0.3s ease;
        }
        
        .kpi-card:hover, .chart-card:hover {
            background: var(--bg-secondary) !important;
            border-color: var(--btn-hover) !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        /* Text Colors */
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary) !important;
        }
        
        p, span, label, .material-icons {
            color: var(--text-secondary) !important;
        }
        
        /* Primary Button */
        .btn-submit, .filter-btn, .export-btn, .action-btn.btn-blue, .action-btn.btn-edit,
        button[type="submit"]:not(.btn-delete), .btn-add, .categories-btn {
            background: var(--btn-bg) !important;
            color: var(--btn-text) !important;
            border: none !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-decoration: none !important;
        }

        .theme-light .btn-submit,
        .theme-light .filter-btn,
        .theme-light .export-btn,
        .theme-light .action-btn.btn-blue,
        .theme-light .action-btn.btn-edit,
        .theme-light button[type="submit"]:not(.btn-delete),
        .theme-light .btn-add,
        .theme-light .categories-btn {
            background: #808080 !important;
            color: #ffffff !important;
        }

        .theme-dark .btn-submit,
        .theme-dark .filter-btn,
        .theme-dark .export-btn,
        .theme-dark .action-btn.btn-blue,
        .theme-dark .action-btn.btn-edit,
        .theme-dark button[type="submit"]:not(.btn-delete),
        .theme-dark .btn-add,
        .theme-dark .categories-btn {
            background: #808080 !important;
            color: #ffffff !important;
        }
        
        .btn-submit:hover, .filter-btn:hover, .export-btn:hover, 
        .action-btn.btn-blue:hover, .action-btn.btn-edit:hover, button[type="submit"]:hover, 
        .btn-add:hover, .categories-btn:hover {
            background: var(--btn-bg) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            color: var(--btn-text) !important;
        }

        .theme-light .btn-submit:hover,
        .theme-light .filter-btn:hover,
        .theme-light .export-btn:hover,
        .theme-light .action-btn.btn-blue:hover,
        .theme-light .action-btn.btn-edit:hover,
        .theme-light button[type="submit"]:hover,
        .theme-light .btn-add:hover,
        .theme-light .categories-btn:hover {
            background: #808080 !important;
            transform: none;
        }

        .theme-dark .btn-submit:hover,
        .theme-dark .filter-btn:hover,
        .theme-dark .export-btn:hover,
        .theme-dark .action-btn.btn-blue:hover,
        .theme-dark .action-btn.btn-edit:hover,
        .theme-dark button[type="submit"]:hover,
        .theme-dark .btn-add:hover,
        .theme-dark .categories-btn:hover {
            background: #808080 !important;
            transform: none;
        }
        
        /* Delete Button - Red */
        .action-btn.btn-delete, .btn-delete, .btn-red,
        button[type="submit"].btn-delete {
            background: #ef4444 !important;
            color: #fff !important;
            border: none !important;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        }
        
        .action-btn.btn-delete:hover, .btn-delete:hover, .btn-red:hover,
        button[type="submit"].btn-delete:hover {
            background: #dc2626 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .theme-light .action-btn.btn-delete,
        .theme-light .btn-delete,
        .theme-light .btn-red,
        .theme-light button[type="submit"].btn-delete {
            background: #808080 !important;
            color: #ffffff !important;
        }

        .theme-light .action-btn.btn-delete:hover,
        .theme-light .btn-delete:hover,
        .theme-light .btn-red:hover,
        .theme-light button[type="submit"].btn-delete:hover {
            background: #808080 !important;
            transform: none;
        }

        .theme-dark .action-btn.btn-delete,
        .theme-dark .btn-delete,
        .theme-dark .btn-red,
        .theme-dark button[type="submit"].btn-delete {
            background: #808080 !important;
            color: #ffffff !important;
        }

        .theme-dark .action-btn.btn-delete:hover,
        .theme-dark .btn-delete:hover,
        .theme-dark .btn-red:hover,
        .theme-dark button[type="submit"].btn-delete:hover {
            background: #808080 !important;
            transform: none;
        }
        
        /* Form Elements */
        input, select, textarea {
            background: var(--input-bg) !important;
            color: var(--input-text) !important;
            border: 1px solid var(--input-border) !important;
            border-radius: 8px;
            padding: 10px;
            transition: all 0.3s ease;
        }

        input::placeholder, textarea::placeholder {
            color: var(--placeholder-color) !important;
        }
        
        input:focus, select:focus, textarea:focus {
            background: var(--bg-secondary) !important;
            border-color: var(--btn-bg) !important;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            outline: none;
        }
        
        /* Tables */
        table {
            background: var(--card-bg) !important;
            color: var(--text-primary);
        }

        .theme-light table {
            color: #000000;
        }

        .theme-dark table {
            color: #ffffff;
        }
        
        table th {
            background: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
            font-weight: 600;
        }

        .theme-light table th {
            color: #000000 !important;
        }

        .theme-dark table th {
            color: #ffffff !important;
        }
        
        table td {
            border-color: var(--border-color) !important;
            color: var(--text-primary);
        }

        .theme-light table td {
            color: #000000;
        }

        .theme-dark table td {
            color: #ffffff;
        }
        
        table tbody tr:hover {
            background: var(--bg-tertiary) !important;
        }
        
        /* Alert Messages */
        .alert-success {
            background: rgba(34, 197, 94, 0.1) !important;
            border: 1px solid rgba(34, 197, 94, 0.3) !important;
            color: #22c55e !important;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1) !important;
            border: 1px solid rgba(239, 68, 68, 0.3) !important;
            color: #ef4444 !important;
        }
        
        /* Links */
        a {
            color: var(--btn-bg) !important;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        a:hover {
            color: var(--btn-hover) !important;
            text-decoration: underline;
        }
        
        /* Theme Toggle Button */
        .theme-toggle {
            position: fixed;
            bottom: 16px;
            left: 16px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
            opacity: 0.5;
            transition: all 250ms ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .theme-toggle:hover {
            opacity: 1;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .theme-toggle .material-icons {
            font-size: 20px;
            color: var(--text-primary);
        }

        .theme-toggle .icon-light,
        .theme-toggle .icon-dark {
            position: absolute;
            transition: opacity 250ms ease, transform 250ms ease;
        }

        .theme-toggle .icon-light {
            opacity: 1;
            transform: scale(1);
        }

        .theme-toggle .icon-dark {
            opacity: 0;
            transform: scale(0.5);
        }

        .theme-dark .theme-toggle .icon-light {
            opacity: 0;
            transform: scale(0.5);
        }

        .theme-dark .theme-toggle .icon-dark {
            opacity: 1;
            transform: scale(1);
        }

        /* Categories Slug Styling */
        .categories-table code {
            background: #808080;
            color: #ffffff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .theme-light .categories-table code {
            background: #808080;
            color: #ffffff;
        }

        .theme-dark .categories-table code {
            background: #555555;
            color: #ffffff;
        }

        /* Analytics Styling - Remove greens and use white cards */
        .kpi-card {
            background: var(--card-bg) !important;
            color: var(--text-primary) !important;
        }

        .theme-light .kpi-card {
            background: #ffffff !important;
            color: #000000 !important;
        }

        .theme-dark .kpi-card {
            background: #323232 !important;
            color: #ffffff !important;
        }

        .kpi-card h3, .kpi-card .kpi-label {
            color: var(--text-primary) !important;
        }

        .theme-light .kpi-card h3,
        .theme-light .kpi-card .kpi-label {
            color: #000000 !important;
        }

        .theme-dark .kpi-card h3,
        .theme-dark .kpi-card .kpi-label {
            color: #ffffff !important;
        }

        /* Analytics Buttons */
        .filter-btn, .export-btn {
            background: var(--btn-bg) !important;
            color: var(--btn-text) !important;
            border: 1px solid var(--border-color) !important;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .theme-light .filter-btn,
        .theme-light .export-btn {
            background: #808080 !important;
            color: #ffffff !important;
            border: 1px solid #808080 !important;
        }

        .theme-light .filter-btn:hover,
        .theme-light .export-btn:hover {
            background: #808080 !important;
            transform: none;
        }

        /* Banners Icon Buttons */
        .btn-icon {
            background: none !important;
            border: none !important;
            color: var(--text-primary) !important;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px;
        }

        .theme-light .btn-icon.btn-delete {
            background: #808080 !important;
            color: #ffffff !important;
        }

        .theme-light .btn-icon.btn-delete:hover {
            background: #808080 !important;
            transform: none;
        }

        .theme-dark .btn-icon.btn-delete {
            background: #ef4444 !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body class="theme-light">
    <!-- Theme Toggle Button -->
    <button class="theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
        <span class="material-icons icon-light">light_mode</span>
        <span class="material-icons icon-dark">dark_mode</span>
    </button>
    <header class="header">
        <div class="logo">
            <img src="{{ asset('images/veyronlogo.jpg') }}" alt="VEYRON Logo">
            <span>Admin Console</span>
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

    <script>
        // Theme Toggle System
        const themeToggle = document.getElementById('themeToggle');
        const htmlElement = document.documentElement;
        const bodyElement = document.body;

        // Initialize theme from localStorage
        function initializeTheme() {
            const savedTheme = localStorage.getItem('admin-theme') || 'theme-light';
            bodyElement.classList.remove('theme-light', 'theme-dark');
            bodyElement.classList.add(savedTheme);
        }

        // Update logo based on theme
        function updateLogoForTheme() {
            const logo = document.querySelector('.logo-container img') || 
                         document.querySelector('.header img[alt*="Logo"]') ||
                         document.querySelector('img[src*="veyronlogo"]');
            
            if (!logo) return;
            
            const isDarkTheme = bodyElement.classList.contains('theme-dark');
            
            if (isDarkTheme) {
                logo.src = '{{ asset("images/veyronlogogrey.png") }}';
                logo.setAttribute('src', '{{ asset("images/veyronlogogrey.png") }}');
            } else {
                logo.src = '{{ asset("images/veyronlogo.jpg") }}';
                logo.setAttribute('src', '{{ asset("images/veyronlogo.jpg") }}');
            }
        }

        // Toggle theme function
        function toggleTheme() {
            const currentTheme = bodyElement.classList.contains('theme-light') ? 'theme-light' : 'theme-dark';
            const newTheme = currentTheme === 'theme-light' ? 'theme-dark' : 'theme-light';
            
            // Remove old theme, add new one
            bodyElement.classList.remove(currentTheme);
            bodyElement.classList.add(newTheme);
            
            // Save preference
            localStorage.setItem('admin-theme', newTheme);
            
            // Update logo
            updateLogoForTheme();
        }

        // Event listener
        themeToggle.addEventListener('click', toggleTheme);

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeTheme();
            updateLogoForTheme();
        });
        initializeTheme();
        updateLogoForTheme();
    </script>
</body>
</html>
