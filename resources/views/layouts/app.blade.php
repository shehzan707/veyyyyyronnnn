<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'VEYRON')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        /* Header Styling */
        .header-wrapper { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.08); position: fixed; top: 0; left: 0; right: 0; z-index: 100; width: 100%; }
        
        /* Main Header */
        .main-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; max-width: 1400px; margin: 0 auto; }
        .logo-container { flex-shrink: 0; margin-left: -75px; }
        .logo-container img { height: 50px; width: auto; }
        
        /* Navigation Menu */
        .nav-menu { display: flex; gap: 40px; flex: 1; margin-left: 60px; }
        .nav-item { position: relative; cursor: pointer; }
        .nav-link { font-size: 13px; font-weight: 600; color: #222; text-decoration: none; padding: 15px 0; display: flex; align-items: center; transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); position: relative; }
        .nav-link::before { content: ''; position: absolute; left: 0; bottom: 0; width: 0%; height: 3px; background: linear-gradient(90deg, #222, #666); transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .nav-link:hover { color: #333; font-weight: 700; letter-spacing: 0.5px; }
        .nav-item:hover .nav-link::before { width: 100%; }
        
        /* Search Bar */
        .search-container { flex: 0.80; margin: 0 2px 0 0; }
        .search-wrapper { position: relative; }
        .search-input { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 2px; font-size: 13px; outline: none; transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); background: #f5f5f5; }
        .search-input:focus { border-color: #666; background: #fff; box-shadow: 0 2px 8px rgba(102,102,102,0.1); transform: scaleY(1.02); }
        .search-input::placeholder { color: #999; }
        .search-dropdown { position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #ddd; border-top: none; max-height: 400px; overflow-y: auto; display: none; z-index: 1000; box-shadow: 0 8px 16px rgba(0,0,0,0.12); opacity: 0; transform: translateY(-5px); transition: all 0.3s ease; }
        .search-dropdown.active { display: block; opacity: 1; transform: translateY(0); }
        .search-item { padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #f0f0f0; font-size: 13px; color: #333; transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94); position: relative; }
        .search-item::before { content: ''; position: absolute; left: 0; top: 0; width: 3px; height: 100%; background: #222; transform: scaleY(0); transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .search-item:hover { background: #f9f9f9; padding-left: 12px; font-weight: 600; }
        .search-item:hover::before { transform: scaleY(1); }
        
        /* User Actions */
        .header-actions { display: flex; gap: 35px; align-items: center; }
        .icon-link { text-decoration: none; color: #333; font-size: 22px; position: relative; transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 5px; border-radius: 4px; }
        .icon-link:hover { color: #222; background: rgba(34, 34, 34, 0.1); transform: scale(1.1); }
        .icon-link.active { color: #666; }
        .cart-badge { position: absolute; top: -8px; right: -12px; background: #666; color: white; font-size: 11px; padding: 2px 6px; border-radius: 10px; min-width: 20px; text-align: center; font-weight: 600; transition: all 0.3s ease; }
        .icon-link:hover .cart-badge { transform: scale(1.15); }
        .profile-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #ddd; color: #222; font-size: 1rem; font-weight: 600; }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        
        /* Mega Menu */
        .mega-menu { position: fixed; top: 60px; left: 0; right: 0; background: #fff; display: none; padding: 40px 60px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); z-index: 999; max-height: calc(100vh - 60px); overflow-y: auto; border-left: 4px solid #666; width: 100%; opacity: 0; transform: translateY(-10px); transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); }
        .mega-menu.active { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 50px; opacity: 1; transform: translateY(0); }
        .mega-menu-column { }
        .mega-menu-column h4 { font-size: 13px; font-weight: 700; color: #666; margin-bottom: 18px; text-transform: uppercase; letter-spacing: 0.8px; transition: all 0.3s ease; }
        .mega-menu-column a { display: block; font-size: 13px; color: #333; text-decoration: none; padding: 10px 0; transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); line-height: 1.8; position: relative; }
        .mega-menu-column a::before { content: ''; position: absolute; left: 0; bottom: 0; width: 0; height: 2px; background: #222; transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .mega-menu-column a:hover { color: #222; font-weight: 600; }
        .mega-menu-column a:hover::before { width: 100%; }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .nav-menu { gap: 35px; margin-left: 40px; }
            .search-container { flex: 0.4; }
            .mega-menu.active { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 40px; padding: 35px; }
        }
        
        @media (max-width: 1024px) {
            .nav-menu { gap: 30px; margin-left:50px; }
            .search-container { flex: 0.45; margin: 0 50px; }
            .header-actions { gap: 20px; }
            .mega-menu.active { grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 30px; padding: 30px; top: 70px; }
        }
        
        @media (max-width: 768px) {
            .main-header { flex-wrap: wrap; padding: 12px 15px; gap: 12px; }
            .logo-container { order: 1; flex-shrink: 0; }
            .search-container { order: 3; flex: 1 1 100%; margin: 10px 0 0 0; }
            .header-actions { order: 2; gap: 15px; }
            .nav-menu { display: none; order: 4; width: 100%; gap: 0; flex-direction: column; margin: 10px 0 0 0; padding-top: 10px; border-top: 1px solid #e0e0e0; }
            .nav-menu.mobile-active { display: flex; }
            .nav-item { width: 100%; }
            .nav-link { padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
            .nav-item:hover .nav-link::before { display: none; }
            .nav-link::before { content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 3px; background: transparent; transition: background 0.3s; }
            .nav-menu.mobile-active .nav-item:hover .nav-link::before { background: #666; }
            .mega-menu { display: none !important; position: static; padding: 0; box-shadow: none; border: none; top: auto; }
            .mega-menu.active { display: none; }
            .mega-menu-column { display: none; }
        }
        
        @media (max-width: 480px) {
            .search-input { font-size: 12px; padding: 8px 12px; }
            .nav-link { font-size: 12px; }
            .header-actions { gap: 10px; }
            .search-container { margin-top: 5px; }
        }
        
        .profile-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #222; color: #fff; font-size: 1.2rem; }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
    </style>
    @stack('styles')
</head>
<body>
    <header class="header-wrapper">
        <div class="main-header">
            <!-- Logo -->
            <div class="logo-container">
                <a href="{{ route('home') }}" style="display: flex; align-items: center; left: -30px;">
                    <img src="{{ asset('images/veyronlogo.jpg') }}" alt="VEYRON Logo">
                </a>
            </div>
            
            <!-- Navigation Menu with Mega Menu -->
            <nav class="nav-menu" id="navMenu">
                @foreach($rootCategories as $category)
                    <div class="nav-item" data-category="{{ strtolower($category->name) }}">
                        <a href="#" class="nav-link">{{ strtoupper($category->name) }}</a>
                        <div class="mega-menu" id="menu-{{ strtolower($category->name) }}">
                            @if($category->children->count() > 0)
                                @foreach($category->children as $child)
                                    <div class="mega-menu-column">
                                        <h4>{{ $child->name }}</h4>
                                        @if($child->children->count() > 0)
                                            @foreach($child->children as $subchild)
                                                <a href="{{ route('products.index', ['categories[]' => $subchild->name]) }}">{{ $subchild->name }}</a>
                                            @endforeach
                                        @else
                                            <a href="{{ route('products.index', ['categories[]' => $child->name]) }}">View All</a>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="mega-menu-column">
                                    <h4>{{ $category->name }}</h4>
                                    <a href="{{ route('products.index', ['categories[]' => $category->name]) }}">View All Products</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </nav>
            
            <!-- Search Bar -->
            <div class="search-container">
                <div class="search-wrapper">
                    <input 
                        type="text" 
                        id="searchInput" 
                        class="search-input" 
                        placeholder="Search for products, categories and brands"
                        autocomplete="off"
                    >
                    <div class="search-dropdown" id="searchDropdown"></div>
                </div>
            </div>
            
            <!-- Header Actions -->
            <div class="header-actions">
                <a href="{{ route('home') }}" class="icon-link" title="Home">
                    <span class="material-icons">home</span>
                </a>
                
                <a href="{{ route('products.index') }}" class="icon-link" title="Products">
                    <span class="material-icons">store</span>
                </a>
                
                <a href="{{ route('wishlist.index') }}" class="icon-link" title="Wishlist">
                    <span class="material-icons">favorite_border</span>
                </a>
                
                <a href="{{ route('cart.index') }}" class="icon-link" title="Cart">
                    <span class="material-icons">shopping_bag</span>
                    @if(session('cart_count', 0) > 0)
                        <span class="cart-badge">{{ session('cart_count') }}</span>
                    @endif
                </a>
                
                @auth
                    <a href="{{ route('account.index') }}" class="icon-link" title="{{ Auth::user()->first_name }}">
                        <div class="profile-avatar">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('uploads/profiles/' . Auth::user()->profile_picture) }}" alt="Profile">
                            @else
                                <span>{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}</span>
                            @endif
                        </div>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="icon-link" title="Account">
                        <span class="material-icons">account_circle</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>
    
    <script>
        // Search functionality data
        const searchData = {
            categories: [
                // Men's categories
                { name: 'T Shirts', category: 'MEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "topwear", "gender" => "men"]) }}' },
                { name: 'Casual Shirt', category: 'MEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "casual-shirts", "gender" => "men"]) }}' },
                { name: 'Formal Shirts', category: 'MEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "formal-shirts", "gender" => "men"]) }}' },
                { name: 'Sweatshirts', category: 'MEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "sweatshirts", "gender" => "men"]) }}' },
                { name: 'Jackets', category: 'MEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "jackets", "gender" => "men"]) }}' },
                { name: 'Blazers', category: 'MEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "blazers", "gender" => "men"]) }}' },
                { name: 'Suits', category: 'MEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "suits", "gender" => "men"]) }}' },
                { name: 'Jeans', category: 'MEN', subcategory: 'Bottomwear', link: '{{ route("products.index", ["category" => "jeans", "gender" => "men"]) }}' },
                { name: 'Trousers', category: 'MEN', subcategory: 'Bottomwear', link: '{{ route("products.index", ["category" => "trousers", "gender" => "men"]) }}' },
                { name: 'Shorts', category: 'MEN', subcategory: 'Bottomwear', link: '{{ route("products.index", ["category" => "shorts", "gender" => "men"]) }}' },
                { name: 'Track Pants', category: 'MEN', subcategory: 'Bottomwear', link: '{{ route("products.index", ["category" => "track-pants", "gender" => "men"]) }}' },
                { name: 'Briefs', category: 'MEN', subcategory: 'Innerwear', link: '{{ route("products.index", ["category" => "briefs", "gender" => "men"]) }}' },
                { name: 'Boxers', category: 'MEN', subcategory: 'Innerwear', link: '{{ route("products.index", ["category" => "boxers", "gender" => "men"]) }}' },
                { name: 'Vests', category: 'MEN', subcategory: 'Innerwear', link: '{{ route("products.index", ["category" => "vests", "gender" => "men"]) }}' },
                { name: 'Sleepwear', category: 'MEN', subcategory: 'Innerwear', link: '{{ route("products.index", ["category" => "sleepwear", "gender" => "men"]) }}' },
                { name: 'Kurtas', category: 'MEN', subcategory: 'Ethnic Wear', link: '{{ route("products.index", ["category" => "kurtas", "gender" => "men"]) }}' },
                { name: 'Nehru Jackets', category: 'MEN', subcategory: 'Ethnic Wear', link: '{{ route("products.index", ["category" => "nehru-jackets", "gender" => "men"]) }}' },
                
                // Women's categories
                { name: 'Tops', category: 'WOMEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "tops", "gender" => "women"]) }}' },
                { name: 'T Shirts', category: 'WOMEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "tshirts", "gender" => "women"]) }}' },
                { name: 'Shirts', category: 'WOMEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "shirts", "gender" => "women"]) }}' },
                { name: 'Sweaters', category: 'WOMEN', subcategory: 'Topwear', link: '{{ route("products.index", ["category" => "sweaters", "gender" => "women"]) }}' },
                { name: 'Jeans', category: 'WOMEN', subcategory: 'Bottomwear', link: '{{ route("products.index", ["category" => "jeans", "gender" => "women"]) }}' },
                { name: 'Trousers', category: 'WOMEN', subcategory: 'Bottomwear', link: '{{ route("products.index", ["category" => "trousers", "gender" => "women"]) }}' },
                { name: 'Skirts', category: 'WOMEN', subcategory: 'Bottomwear', link: '{{ route("products.index", ["category" => "skirts", "gender" => "women"]) }}' },
                { name: 'Palazzos', category: 'WOMEN', subcategory: 'Bottomwear', link: '{{ route("products.index", ["category" => "palazzos", "gender" => "women"]) }}' },
                { name: 'Kurtis', category: 'WOMEN', subcategory: 'Ethnic Wear', link: '{{ route("products.index", ["category" => "kurtis", "gender" => "women"]) }}' },
                { name: 'Kurta Sets', category: 'WOMEN', subcategory: 'Ethnic Wear', link: '{{ route("products.index", ["category" => "kurta-sets", "gender" => "women"]) }}' },
                { name: 'Dupattas', category: 'WOMEN', subcategory: 'Ethnic Wear', link: '{{ route("products.index", ["category" => "dupattas", "gender" => "women"]) }}' },
                { name: 'Nightwear', category: 'WOMEN', subcategory: 'Loungewear', link: '{{ route("products.index", ["category" => "nightwear", "gender" => "women"]) }}' },
                { name: 'Thermals', category: 'WOMEN', subcategory: 'Loungewear', link: '{{ route("products.index", ["category" => "thermals", "gender" => "women"]) }}' },
                
                // Accessories
                { name: 'Wallets', category: 'ACCESSORIES', subcategory: 'Accessories', link: '{{ route("products.index", ["category" => "wallets"]) }}' },
                { name: 'Belts', category: 'ACCESSORIES', subcategory: 'Accessories', link: '{{ route("products.index", ["category" => "belts"]) }}' },
                { name: 'Watches', category: 'ACCESSORIES', subcategory: 'Accessories', link: '{{ route("products.index", ["category" => "watches"]) }}' },
                { name: 'Sunglasses', category: 'ACCESSORIES', subcategory: 'Accessories', link: '{{ route("products.index", ["category" => "sunglasses"]) }}' },
                { name: 'Caps and Hats', category: 'ACCESSORIES', subcategory: 'Accessories', link: '{{ route("products.index", ["category" => "caps-hats"]) }}' },
                { name: 'Scarves', category: 'ACCESSORIES', subcategory: 'Accessories', link: '{{ route("products.index", ["category" => "scarves"]) }}' },
                { name: 'Rings', category: 'ACCESSORIES', subcategory: 'Accessories', link: '{{ route("products.index", ["category" => "rings"]) }}' },
                { name: 'Bracelets', category: 'ACCESSORIES', subcategory: 'Accessories', link: '{{ route("products.index", ["category" => "bracelets"]) }}' },
                { name: 'Backpacks', category: 'ACCESSORIES', subcategory: 'Accessories', link: '{{ route("products.index", ["category" => "backpacks"]) }}' },
                
                // Footwear
                { name: 'Casual Shoes', category: 'FOOTWEAR', subcategory: 'Footwear', link: '{{ route("products.index", ["category" => "casual-shoes"]) }}' },
                { name: 'Formal Shoes', category: 'FOOTWEAR', subcategory: 'Footwear', link: '{{ route("products.index", ["category" => "formal-shoes"]) }}' },
                { name: 'Sneakers', category: 'FOOTWEAR', subcategory: 'Footwear', link: '{{ route("products.index", ["category" => "sneakers"]) }}' },
                { name: 'Sports Shoes', category: 'FOOTWEAR', subcategory: 'Footwear', link: '{{ route("products.index", ["category" => "sports-shoes"]) }}' },
                { name: 'Sandals', category: 'FOOTWEAR', subcategory: 'Footwear', link: '{{ route("products.index", ["category" => "sandals"]) }}' },
                { name: 'Floaters', category: 'FOOTWEAR', subcategory: 'Footwear', link: '{{ route("products.index", ["category" => "floaters"]) }}' },
                { name: 'Flip Flops', category: 'FOOTWEAR', subcategory: 'Footwear', link: '{{ route("products.index", ["category" => "flip-flops"]) }}' },
            ]
        };
        
        // Mega Menu Toggle
        document.querySelectorAll('.nav-item').forEach(item => {
            const link = item.querySelector('.nav-link');
            const menu = item.querySelector('.mega-menu');
            const category = item.dataset.category;
            
            item.addEventListener('mouseenter', () => {
                document.querySelectorAll('.mega-menu.active').forEach(m => {
                    if (m !== menu) m.classList.remove('active');
                });
                menu.classList.add('active');
            });
            
            item.addEventListener('mouseleave', () => {
                menu.classList.remove('active');
            });
            
            // Click handler - redirect based on current page
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Check if we're on home page or products page
                const isHomePage = window.location.pathname === '/' || 
                                   window.location.pathname.includes('/men') ||
                                   window.location.pathname.includes('/women') ||
                                   window.location.pathname.includes('/accessories') ||
                                   window.location.pathname.includes('/footwear');
                
                const isProductPage = window.location.pathname.includes('/products');
                
                if (isProductPage) {
                    // If on products page, go to products with gender filter
                    const productRoutes = {
                        'men': '{{ route("products.index", ["gender" => "men"]) }}',
                        'women': '{{ route("products.index", ["gender" => "women"]) }}',
                        'accessories': '{{ route("products.index", ["gender" => "accessories"]) }}',
                        'footwear': '{{ route("products.index", ["gender" => "footwear"]) }}'
                    };
                    if (productRoutes[category]) {
                        window.location.href = productRoutes[category];
                    }
                } else {
                    // If on home page, go to category home page
                    const homeRoutes = {
                        'men': '{{ route("home.men") }}',
                        'women': '{{ route("home.women") }}',
                        'accessories': '{{ route("home.accessories") }}',
                        'footwear': '{{ route("home.footwear") }}'
                    };
                    if (homeRoutes[category]) {
                        window.location.href = homeRoutes[category];
                    }
                }
            });
        });
        
        // Close mega menu when clicking elsewhere
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.nav-item')) {
                document.querySelectorAll('.mega-menu.active').forEach(m => {
                    m.classList.remove('active');
                });
            }
        });
        
        // Smart Search Functionality
        const searchInput = document.getElementById('searchInput');
        const searchDropdown = document.getElementById('searchDropdown');
        
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            
            if (query.length === 0) {
                searchDropdown.classList.remove('active');
                return;
            }
            
            const results = searchData.categories.filter(item => 
                item.name.toLowerCase().includes(query) ||
                item.category.toLowerCase().includes(query) ||
                item.subcategory.toLowerCase().includes(query)
            ).slice(0, 8);
            
            if (results.length === 0) {
                searchDropdown.innerHTML = '<div class="search-item">No results found</div>';
                searchDropdown.classList.add('active');
                return;
            }
            
            searchDropdown.innerHTML = results.map(item => `
                <a href="${item.link}" style="text-decoration: none; color: inherit;">
                    <div class="search-item">
                        <div style="font-weight: 500; color: #222;">${item.name}</div>
                        <div class="search-label">${item.category} > ${item.subcategory}</div>
                    </div>
                </a>
            `).join('');
            
            searchDropdown.classList.add('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-wrapper')) {
                searchDropdown.classList.remove('active');
            }
        });
        
        // Allow Enter key to search
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const query = searchInput.value.toLowerCase().trim();
                if (query) {
                    window.location.href = `{{ route('products.index') }}?search=${encodeURIComponent(query)}`;
                }
            }
        });
    </script>

    <main style="min-height: calc(100vh - 300px); margin-top: 70px;">
        @yield('content')
    </main>

    <footer class="veyron-footer">
        <style>
            .veyron-footer {
                background: #f9f9f9;
                color: #333;
                padding: 60px 20px 40px;
                border-top: 1px solid #e8e8e8;
                font-family: 'Poppins', sans-serif;
            }

            .veyron-footer .footer-container {
                max-width: 1400px;
                margin: 0 auto;
            }

            .veyron-footer .footer-main {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 50px;
                margin-bottom: 50px;
            }

            .veyron-footer .footer-section h4 {
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1.2px;
                color: #222;
                margin-bottom: 22px;
                display: block;
            }

            .veyron-footer .footer-section a,
            .veyron-footer .footer-section p {
                font-size: 12px;
                color: #666;
                margin-bottom: 14px;
                text-decoration: none;
                line-height: 1.7;
                transition: color 0.3s ease;
                display: block;
            }

            .veyron-footer .footer-section a:hover {
                color: #222;
            }

            .veyron-footer .footer-section p {
                margin-bottom: 10px;
                color: #777;
                line-height: 1.6;
            }

            .veyron-footer .benefits-section {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 40px;
                padding: 40px 0;
                border-top: 1px solid #e8e8e8;
                border-bottom: 1px solid #e8e8e8;
                margin-bottom: 40px;
            }

            .veyron-footer .benefit-item {
                display: flex;
                gap: 20px;
            }

            .veyron-footer .benefit-icon {
                font-size: 28px;
                color: #222;
                flex-shrink: 0;
                line-height: 1;
            }

            .veyron-footer .benefit-text h5 {
                font-size: 12px;
                font-weight: 700;
                color: #222;
                margin-bottom: 6px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }

            .veyron-footer .benefit-text p {
                font-size: 12px;
                color: #777;
                margin: 0;
            }

            .veyron-footer .footer-apps {
                display: flex;
                gap: 12px;
                margin-top: 18px;
            }

            .veyron-footer .footer-apps img {
                height: 36px;
                cursor: pointer;
                transition: opacity 0.3s ease;
            }

            .veyron-footer .footer-apps img:hover {
                opacity: 0.8;
            }

            .veyron-footer .social-section h5 {
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1.2px;
                color: #222;
                margin: 20px 0 16px 0;
            }

            .veyron-footer .social-links {
                display: flex;
                gap: 12px;
            }

            .veyron-footer .social-links a {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 32px;
                height: 32px;
                background: #e8e8e8;
                border-radius: 50%;
                color: #222;
                font-size: 14px;
                transition: all 0.3s ease;
                text-decoration: none;
                margin-bottom: 0;
            }

            .veyron-footer .social-links a:hover {
                background: #222;
                color: #fff;
            }

            .veyron-footer .footer-bottom {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 30px;
                font-size: 11px;
                color: #999;
                padding: 25px 0;
            }

            .veyron-footer .footer-bottom p {
                margin: 0;
                font-size: 11px;
            }

            .veyron-footer .footer-bottom strong {
                color: #333;
                font-weight: 600;
            }

            @media (max-width: 1024px) {
                .veyron-footer .footer-main {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 40px;
                }

                .veyron-footer .benefits-section {
                    grid-template-columns: 1fr;
                    gap: 30px;
                }
            }

            @media (max-width: 768px) {
                .veyron-footer {
                    padding: 40px 20px 30px;
                }

                .veyron-footer .footer-main {
                    grid-template-columns: 1fr;
                    gap: 30px;
                    margin-bottom: 35px;
                }

                .veyron-footer .benefits-section {
                    padding: 30px 0;
                }

                .veyron-footer .footer-bottom {
                    flex-direction: column;
                    text-align: center;
                    gap: 15px;
                }
            }
    
        </style>

        <div class="footer-container">
            <!-- Main Footer Sections -->
            <div class="footer-main">
                <!-- Online Shopping -->
                <div class="footer-section">
                    <h4>Online Shopping</h4>
                    <a href="{{ route('products.index', ['gender' => 'men']) }}">Men</a>
                    <a href="{{ route('products.index', ['gender' => 'women']) }}">Women</a>
                    <a href="{{ route('products.index', ['gender' => 'accessories']) }}">Accessories</a>
                    <a href="{{ route('products.index', ['gender' => 'footwear']) }}">Footwear</a>
                    <a href="{{ route('products.index') }}">All Collections</a>
                </div>

                <!-- Customer Policies -->
                <div class="footer-section">
                    <h4>Customer Policies</h4>
                    <a href="#contact">Contact Us</a>
                    <a href="#faq">FAQ</a>
                    <a href="#terms">Terms & Conditions</a>
                    <a href="#track">Track Orders</a>
                    <a href="#shipping">Shipping Info</a>
                    <a href="#returns">Returns & Refunds</a>
                    <a href="#privacy">Privacy Policy</a>
                </div>

                <!-- About Veyron -->
                <div class="footer-section">
                    <h4>About Veyron</h4>
                    <p>Premium fashion and lifestyle destination. Curated collections with guaranteed authenticity and quality.</p>
                    <a href="#careers">Careers</a>
                    <a href="#blog">Blog</a>
                    <a href="#sitemap">Site Map</a>
                    <a href="#corporate">Corporate Info</a>
                </div>

                <!-- Experience Veyron -->
                <div class="footer-section">
                    <h4>Experience Veyron</h4>
                    <p>Get the app for exclusive deals and faster checkout</p>
                    <div class="footer-apps">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 36'%3E%3Crect fill='%23222' width='120' height='36' rx='4'/%3E%3Ctext x='50%' y='50%' text-anchor='middle' dy='.3em' fill='white' font-family='Poppins' font-size='11' font-weight='bold'%3EGoogle Play%3C/text%3E%3C/svg%3E" alt="Google Play">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 36'%3E%3Crect fill='%23222' width='120' height='36' rx='4'/%3E%3Ctext x='50%' y='50%' text-anchor='middle' dy='.3em' fill='white' font-family='Poppins' font-size='11' font-weight='bold'%3EApp Store%3C/text%3E%3C/svg%3E" alt="App Store">
                    </div>
                    <div class="social-section">
                        <h5>Keep In Touch</h5>
                        <div class="social-links">
                            <a href="#facebook" title="Facebook">f</a>
                            <a href="#twitter" title="Twitter">𝕏</a>
                            <a href="#instagram" title="Instagram">◉</a>
                            <a href="#youtube" title="YouTube">▶</a>
                        </div>
                    </div>
                </div>

                <!-- Other Links -->
                <div class="footer-section">
                    <h4>More Information</h4>
                    <a href="#useful">Useful Links</a>
                    <a href="#investors">Investor Relations</a>
                    <a href="#sustainability">Sustainability</a>
                    <a href="#newsroom">Newsroom</a>
                    <a href="#affiliates">Affiliate Program</a>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="benefits-section">
                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div class="benefit-text">
                        <h5>100% Original</h5>
                        <p>Guaranteed for all products at veyron.com</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">↩</div>
                    <div class="benefit-text">
                        <h5>Return within 14 days</h5>
                        <p>Of receiving your order</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">⚡</div>
                    <div class="benefit-text">
                        <h5>Free Shipping</h5>
                        <p>On orders above ₹15000</p>
                    </div>
                </div>
            </div>

            <!-- Copyright & Company Info -->
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} <strong>VEYRON</strong>. All rights reserved.</p>
                <p><strong>Premium Fashion Destination</strong></p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
