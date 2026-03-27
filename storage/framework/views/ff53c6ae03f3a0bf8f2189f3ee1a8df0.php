<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('title', 'VEYRON'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { padding-top: 80px !important; }
        
        /* Header Styling */
        .header-wrapper { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.08); position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; z-index: 100 !important; width: 100% !important; }
        
        /* Main Header */
        .main-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; max-width: 1400px; margin: 0 auto; }
        .logo-container { flex-shrink: 0; margin-left: -75px; }
        .logo-container img { height: 50px; width: auto; }
        
        /* Navigation Menu */
        .nav-menu { display: flex; gap: 40px; flex: 1; margin-left: 60px; }
        .nav-item { position: relative; cursor: pointer; }
        .nav-link { font-size: 13px; font-weight: 600; color: #222; text-decoration: none; padding: 15px 0; display: flex; align-items: center; transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); position: relative; z-index: 101; cursor: pointer; }
        .nav-link::before { content: ''; position: absolute; left: 0; bottom: 0; width: 0%; height: 3px; background: linear-gradient(90deg, #222, #666); transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); display: none; }
        .nav-link:hover { color: #333; font-weight: 700; letter-spacing: 0.5px; }
        
        /* Search Bar */
        .search-container { flex: 0.55; margin: 0 1px 0 3px; }
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
        .mega-menu { position: fixed; top: 74px; left: 0; right: 0; background: #fff; display: none; padding: 40px 60px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); z-index: 99; max-height: 600px; overflow-y: auto; border-left: 4px solid #666; width: 100%; opacity: 0; transform: translateY(-10px); transition: all 0.3s ease; }
        .mega-menu.active { display: grid; grid-template-columns: repeat(5, 1fr); gap: 50px; opacity: 1; transform: translateY(0); }
        .mega-menu-column h4 { font-size: 13px; font-weight: 700; color: #666; margin-bottom: 18px; text-transform: uppercase; letter-spacing: 0.8px; transition: all 0.3s ease; }
        .mega-menu-column a { display: block; font-size: 13px; color: #333; text-decoration: none; padding: 10px 0; transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); line-height: 1.8; position: relative; }
        .mega-menu-column a::before { content: ''; position: absolute; left: 0; bottom: 0; width: 0; height: 2px; background: #222; transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); display: none; }
        .mega-menu-column a:hover { color: #222; font-weight: 600; }
        
        /* Navigation Images - Individual Sizes (Edit Each Category Separately) */
        [data-category="men"] img { 
            height: 40px; 
            width: auto; 
            max-width: 75px; 
            object-fit: contain;
        }
        [data-category="women"] img { 
            height: 39px; 
            width: auto; 
            max-width: 85px; 
            object-fit: contain;
        }
        [data-category="accessories"] img { 
            height: 39px; 
            width: auto; 
            max-width: 125px; 
            object-fit: contain;
        }
        [data-category="footwear"] img { 
            height: 40px; 
            width: auto; 
            max-width: 119px; 
            object-fit: contain;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .nav-menu { gap: 35px; margin-left: 40px; }
            .search-container { flex: 0.4; }
            .mega-menu.active { grid-template-columns: repeat(4, 1fr); gap: 40px; padding: 35px; }
        }
        
        @media (max-width: 1024px) {
            .nav-menu { gap: 30px; margin-left:50px; }
            .search-container { flex: 0.45; margin: 0 50px; }
            .header-actions { gap: 20px; }
            .mega-menu.active { grid-template-columns: repeat(3, 1fr); gap: 30px; padding: 30px; top: 70px; }
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
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <header class="header-wrapper">
        <div class="main-header">
            <!-- Logo -->
            <div class="logo-container">
                <a href="<?php echo e(route('home')); ?>" style="display: flex; align-items: center; left: -30px;">
                    <img src="<?php echo e(asset('images/veyronlogo.jpg')); ?>" alt="VEYRON Logo">
                </a>
            </div>
            
            <!-- Navigation Menu with Mega Menu -->
            <nav class="nav-menu" id="navMenu">
                <?php if(isset($nav_categories)): ?>
                    <?php $__currentLoopData = $nav_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Normalize category name for mapping
                            $categoryLower = strtolower($category->name);
                            
                            // Map category names to gender parameter values
                            $genderMap = [
                                'men' => 'men',
                                'women' => 'women',
                                'accessories' => 'accessories',
                                'footwear' => 'footwear'
                            ];
                            $genderParam = $genderMap[$categoryLower] ?? null;
                            
                            // Map category names to home route names
                            $homeRouteMap = [
                                'men' => 'home.men',
                                'women' => 'home.women',
                                'accessories' => 'home.accessories',
                                'footwear' => 'home.footwear'
                            ];
                            
                            // Navigate to the home page for this category with fallback to products page
                            $navLink = '#';
                            if (isset($homeRouteMap[$categoryLower])) {
                                try {
                                    $navLink = route($homeRouteMap[$categoryLower]);
                                } catch (\Exception $e) {
                                    // Fallback if route doesn't exist
                                    if ($genderParam) {
                                        $navLink = route('products.index', ['gender' => $genderParam]);
                                    }
                                }
                            } elseif ($genderParam) {
                                $navLink = route('products.index', ['gender' => $genderParam]);
                            }
                        ?>
                        <div class="nav-item" data-category="<?php echo e(strtolower($category->slug)); ?>">
                            <a href="<?php echo e($navLink); ?>" class="nav-link">
                                <?php
                                    $imageMap = [
                                        'Men' => 'men.jpeg',
                                        'MEN' => 'men.jpeg',
                                        'Women' => 'women.jpeg',
                                        'WOMEN' => 'women.jpeg',
                                        'Accessories' => 'acc.jpeg',
                                        'ACCESSORIES' => 'acc.jpeg',
                                        'Footwear' => 'foot.jpeg',
                                        'FOOTWEAR' => 'foot.jpeg'
                                    ];
                                    $imageName = $imageMap[$category->name] ?? 'men.jpeg';
                                ?>
                                <img src="<?php echo e(asset('images/' . $imageName)); ?>" alt="<?php echo e($category->name); ?>" style="object-fit: contain;">
                            </a>
                            <?php if($category->children && count($category->children) > 0): ?>
                                <div class="mega-menu" id="menu-<?php echo e(strtolower($category->slug)); ?>">
                                    <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            // Hide Accessories and Footwear from Women header menu
                                            $hiddenCategoriesForWomen = ['Accessories', 'Footwear'];
                                            $shouldHide = ($category->name === 'Women' && in_array($child->name, $hiddenCategoriesForWomen));
                                        ?>
                                        <?php if($index < 5 && !$shouldHide): ?>
                                            <div class="mega-menu-column">
                                                <h4><?php echo e($child->name); ?></h4>
                                                <?php if($child->children && count($child->children) > 0): ?>
                                                    <?php $__currentLoopData = $child->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subchild): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <a href="<?php echo e(route('products.index', ['gender' => $genderParam, 'category' => $subchild->slug])); ?>"><?php echo e($subchild->name); ?></a>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('products.index', ['gender' => $genderParam, 'category' => $child->slug])); ?>">View All</a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="mega-menu" id="menu-<?php echo e(strtolower($category->slug)); ?>">
                                    <div class="mega-menu-column">
                                        <h4><?php echo e($category->name); ?></h4>
                                        <a href="<?php echo e(route('products.index', ['gender' => $genderParam])); ?>">View All</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
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
                <a href="<?php echo e(route('home')); ?>" class="icon-link" title="Home">
                    <span class="material-icons">home</span>
                </a>
                
                <a href="<?php echo e(route('products.index')); ?>" class="icon-link" title="Products">
                    <span class="material-icons">store</span>
                </a>
                
                <a href="<?php echo e(route('wishlist.index')); ?>" class="icon-link" title="Wishlist">
                    <span class="material-icons">favorite_border</span>
                </a>
                
                <a href="<?php echo e(route('cart.index')); ?>" class="icon-link" title="Cart">
                    <span class="material-icons">shopping_bag</span>
                    <?php if(session('cart_count', 0) > 0): ?>
                        <span class="cart-badge"><?php echo e(session('cart_count')); ?></span>
                    <?php endif; ?>
                </a>
                
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('account.index')); ?>" class="icon-link" title="<?php echo e(Auth::user()->first_name); ?>">
                        <div class="profile-avatar">
                            <?php if(Auth::user()->profile_picture): ?>
                                <img src="<?php echo e(asset('uploads/profiles/' . Auth::user()->profile_picture)); ?>" alt="Profile">
                            <?php else: ?>
                                <span><?php echo e(strtoupper(substr(Auth::user()->first_name, 0, 1))); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="icon-link" title="Account">
                        <span class="material-icons">account_circle</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <script>
        // Search functionality data - generated dynamically from database categories
        const searchData = {
            categories: [
                <?php if(isset($nav_categories)): ?>
                    <?php
                        $searchItems = [];
                        foreach($nav_categories as $category) {
                            if($category->children && count($category->children) > 0) {
                                foreach($category->children as $child) {
                                    if($child->children && count($child->children) > 0) {
                                        foreach($child->children as $subchild) {
                                            $searchItems[] = ['name' => $subchild->name, 'category' => strtoupper($category->name), 'subcategory' => $child->name, 'slug' => $subchild->slug];
                                        }
                                    } else {
                                        $searchItems[] = ['name' => $child->name, 'category' => strtoupper($category->name), 'subcategory' => $child->name, 'slug' => $child->slug];
                                    }
                                }
                            } else {
                                $searchItems[] = ['name' => $category->name, 'category' => strtoupper($category->name), 'subcategory' => 'All', 'slug' => $category->slug];
                            }
                        }
                    ?>
                    <?php $__currentLoopData = $searchItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        { name: '<?php echo e($item['name']); ?>', category: '<?php echo e($item['category']); ?>', subcategory: '<?php echo e($item['subcategory']); ?>', link: '<?php echo e(route("products.index", ["category" => $item['slug']])); ?>' }<?php echo e(!$loop->last ? ',' : ''); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            ]
        };
        
        // Mega Menu Toggle
        document.querySelectorAll('.nav-item').forEach(item => {
            const link = item.querySelector('.nav-link');
            const menu = item.querySelector('.mega-menu');
            const category = item.dataset.category;
            
            // Show mega menu on hover
            item.addEventListener('mouseenter', () => {
                document.querySelectorAll('.mega-menu.active').forEach(m => {
                    if (m !== menu) m.classList.remove('active');
                });
                menu.classList.add('active');
            });
            
            item.addEventListener('mouseleave', () => {
                menu.classList.remove('active');
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
                    window.location.href = `<?php echo e(route('products.index')); ?>?search=${encodeURIComponent(query)}`;
                }
            }
        });
    </script>

    <main style="min-height: calc(100vh - 300px);">
        <?php echo $__env->yieldContent('content'); ?>
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
                    <a href="<?php echo e(route('products.index', ['gender' => 'men'])); ?>">Men</a>
                    <a href="<?php echo e(route('products.index', ['gender' => 'women'])); ?>">Women</a>
                    <a href="<?php echo e(route('products.index', ['gender' => 'accessories'])); ?>">Accessories</a>
                    <a href="<?php echo e(route('products.index', ['gender' => 'footwear'])); ?>">Footwear</a>
                    <a href="<?php echo e(route('products.index')); ?>">All Collections</a>
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
                <p>&copy; <?php echo e(date('Y')); ?> <strong>VEYRON</strong>. All rights reserved.</p>
                <p><strong>Premium Fashion Destination</strong></p>
            </div>
        </div>
    </footer>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Veyronnnnnnnnnn\resources\views/layouts/app.blade.php ENDPATH**/ ?>