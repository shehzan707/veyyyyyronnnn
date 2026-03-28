<?php $__env->startSection('title', 'VEYRON — Footwear'); ?>

<?php $__env->startPush('styles'); ?>
<style>
:root{
    --black:#080808;
    --dark:#121212;
    --mid:#7a7a7a;
    --light:#ededed;
    --white:#ffffff;
}

/* RESET */
*{box-sizing:border-box}
body{
    margin:0;
    font-family:'Poppins',system-ui,sans-serif;
    background:var(--white);
    color:var(--black);
}

/* FULL BLEED CONTAINER */
.home-container{
    max-width:100%;
    margin:0;
    padding:2px;
}

/* GROUP */
.subcategory-group{
    margin:6rem 0 8rem;
    padding:0 2.2rem;
}

/* TITLES */
.subcategory-group-title{
    font-size:3.2rem;
    font-weight:900;
    letter-spacing:5px;
    text-transform:uppercase;
    margin-bottom:4.8rem;
    position:relative;
    color:#000;
}

/* GHOST BRAND */
.subcategory-group-title::before{
    content:'VEYRON';
    position:absolute;
    top:-2.4rem;
    left:0;
    font-size:7.2rem;
    font-weight:900;
    letter-spacing:10px;
    color:rgba(0,0,0,0.12);
    pointer-events:none;
}

/* HARD UNDERLINE */
.subcategory-group-title::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-18px;
    width:90px;
    height:3px;
    background:#000;
}

/* GRID */
.subcategory-cards-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
    gap:3.4rem;
}

/* CARD – EDGY */
.subcategory-card{
    position:relative;
    height:540px;
    background:#000;
    border-radius:6px;
    overflow:hidden;
    text-decoration:none;
    color:#fff;
    transform-style:preserve-3d;
    transition:
        transform .9s cubic-bezier(.23,1,.32,1),
        box-shadow .9s cubic-bezier(.23,1,.32,1);
    box-shadow:0 30px 60px rgba(0,0,0,.35);
}

.subcategory-card:hover{
    transform:translateY(-26px) rotateX(7deg);
    box-shadow:0 90px 140px rgba(0,0,0,.55);
}

/* IMAGE */
.subcategory-card-image{
    position:absolute;
    inset:0;
    overflow:hidden;
}

.subcategory-card-image img{
    width:100%;
    height:100%;
    object-fit:cover;
    transform:scale(1.08);
    transition:transform 1.2s cubic-bezier(.23,1,.32,1),
               filter .8s ease;
}

.subcategory-card:hover img{
    transform:scale(1.02);
    filter:grayscale(.05) contrast(1.1);
}

/* MASK */
/* Black shade overlay removed */

/* CONTENT */
.subcategory-card-content{
    position:absolute;
    bottom:0;
    width:100%;
    padding:3.2rem 2.6rem;
    transform:translateY(46px);
    transition:transform .8s cubic-bezier(.23,1,.32,1);
    z-index:2;
}

.subcategory-card:hover .subcategory-card-content{
    transform:translateY(0);
}

/* NAME */
.subcategory-card-name{
    font-size:1.9rem;
    font-weight:900;
    letter-spacing:1.6px;
    margin:0 0 .8rem;
}

/* TAGLINE */
.subcategory-card-tagline{
    font-size:1.15rem;
    color:#666;
    letter-spacing:.8px;
    line-height:1.8;
    text-transform:capitalize;
    transition:color .7s ease, transform .7s ease;
}

.subcategory-card:hover .subcategory-card-tagline{
    color:#000;
    transform:translateY(-2px);
}

/* ACCENT LINE */
.subcategory-card-content::before{
    content:'';
    position:absolute;
    top:-22px;
    left:2.6rem;
    width:72px;
    height:3px;
    background:#fff;
    transform:scaleX(0);
    transform-origin:left;
    transition:transform .8s cubic-bezier(.23,1,.32,1);
    display:none;
}

/* RESPONSIVE */
@media(max-width:900px){
    .subcategory-group-title{font-size:2.4rem}
    .subcategory-group-title::before{font-size:5.2rem}
    .subcategory-card{height:440px}
}

@media(max-width:600px){
    .subcategory-group{padding:0 1.4rem}
    .subcategory-cards-grid{gap:2.2rem}
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('components.banner-carousel', [
    'banners' => $banners,
    'section' => 'footwear'
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="home-container">

    <!-- MENS FOOTWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Mens Curated Sneakers</h2>
        <div class="subcategory-cards-grid">

            <!-- M01 - CASUAL SHOES -->
            <a href="<?php echo e(url('products')); ?>?gender=Footwear&categories[]=sneakers&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="<?php echo e(asset('images/m01.jpg')); ?>" alt="Casual Shoes">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Comfort wrapped in contemporary style</p>
                </div>
            </a>

            <!-- M02 - SPORTS SHOES -->
            <a href="<?php echo e(url('products')); ?>?gender=Footwear&categories[]=sneakers&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="<?php echo e(asset('images/m02.jpg')); ?>" alt="Sports Shoes">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Performance designed for active movement</p>
                </div>
            </a>

            <!-- M03 - FORMAL SHOES -->
            <a href="<?php echo e(url('products')); ?>?gender=Footwear&categories[]=sneakers&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="<?php echo e(asset('images/m03.jpg')); ?>" alt="Formal Shoes">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Elegance grounded in timeless luxury</p>
                </div>
            </a>

            <!-- M04 - HEELS -->
            <a href="<?php echo e(url('products')); ?>?gender=Footwear&categories[]=sneakers&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="<?php echo e(asset('images/m04.jpeg')); ?>" alt="Heels">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Refined height meets sophisticated grace</p>
                </div>
            </a>

        </div>
    </div>

    <!-- WOMENS FOOTWEAR -->
    <div class="subcategory-group">
        <h2 class="subcategory-group-title">Womens Curated Sneakers</h2>
        <div class="subcategory-cards-grid">

            <!-- W01 - BOOTS -->
            <a href="<?php echo e(url('products')); ?>?gender=Footwear&categories[]=sneaker&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="<?php echo e(asset('images/w01.jpeg')); ?>" alt="Boots">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Structured strength with refined character</p>
                </div>
            </a>

            <!-- W02 - SANDALS -->
            <a href="<?php echo e(url('products')); ?>?gender=Footwear&categories[]=sneaker&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="<?php echo e(asset('images/w02.jpg')); ?>" alt="Sandals">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Casual freedom with effortless elegance</p>
                </div>
            </a>

            <!-- W03 - BOOTS -->
            <a href="<?php echo e(url('products')); ?>?gender=Footwear&categories[]=sneaker&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="<?php echo e(asset('images/w03.png')); ?>" alt="Boots">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Structured strength with refined character</p>
                </div>
            </a>

            <!-- W04 - SANDALS -->
            <a href="<?php echo e(url('products')); ?>?gender=Footwear&categories[]=sneaker&min_price=0&max_price=100000" class="subcategory-card">
                <div class="subcategory-card-image">
                    <img src="<?php echo e(asset('images/w04.jpg')); ?>" alt="Sandals">
                </div>
                <div class="subcategory-card-content">
                    <p class="subcategory-card-tagline">Casual freedom with effortless elegance</p>
                </div>
            </a>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Veyronnnnnnnnnn\resources\views/shop/home-footwear.blade.php ENDPATH**/ ?>