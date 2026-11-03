<?php
// header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Bean & Brew | Artisanal Coffee'; ?></title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/bootstrap.min.css">
    <style>
        :root { --coffee-ink: #2b211d; --coffee-brown: #56392d; --coffee-gold: #d9a86c; --coffee-cream: #f7f3ed; }
        body { background: var(--coffee-cream); color: var(--coffee-ink); min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; padding-top: 1.5rem; }
        main.home-page { padding-top: 0; }
        .site-nav { background: rgba(43, 33, 29, .97); }
        .site-nav .navbar-brand { color: #fff; font-weight: 750; letter-spacing: -.03em; }
        .site-nav .nav-link { color: rgba(255,255,255,.82); }
        .site-nav .nav-link:hover, .site-nav .nav-link:focus { color: var(--coffee-gold); }
        .navbar-tools { flex-shrink: 0; }
        .cart-link { display: inline-flex; align-items: center; gap: .45rem; color: var(--coffee-ink) !important; background: var(--coffee-gold); border: 1px solid var(--coffee-gold); border-radius: 999px; padding: .45rem .8rem !important; font-size: .9rem; font-weight: 700; line-height: 1; text-decoration: none; transition: background-color .2s ease, border-color .2s ease, transform .2s ease; }
        .cart-link:hover, .cart-link:focus { color: var(--coffee-ink) !important; background: #e6bb86; border-color: #e6bb86; transform: translateY(-1px); }
        .cart-link svg { width: 1.1rem; height: 1.1rem; }
        .cart-count { min-width: 1.25rem; height: 1.25rem; display: inline-grid; place-items: center; padding: 0 .25rem; border-radius: 999px; background: var(--coffee-ink); color: #fff; font-size: .7rem; }
        .site-navigation { --bs-offcanvas-width: min(85vw, 360px); background: var(--coffee-ink); }
        .site-navigation .offcanvas-title { color: #fff; font-weight: 750; }
        @media (max-width: 991.98px) {
            .site-navigation .navbar-nav { padding: .5rem 1rem 1rem; }
            .site-navigation .nav-link { padding: .8rem 1rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        }
        .hero-panel { min-height: 390px; display: grid; align-items: center; color: white; background: linear-gradient(90deg, rgba(28,20,16,.82), rgba(28,20,16,.2)), url('assets/images/hero.jpg') center/cover; }
        .product-card { overflow: hidden; border: 1px solid rgba(65,44,34,.08); border-radius: 1rem; transition: transform .2s ease, box-shadow .2s ease; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 1rem 2rem rgba(43,33,29,.12); }
        .product-art { min-height: 150px; display: grid; place-items: center; background: linear-gradient(135deg, #efe2d1, #dfc5a5); font-size: 4rem; }
        .site-footer { background: var(--coffee-ink); color: rgba(255,255,255,.8); }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; } }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark site-nav sticky-top">
    <div class="container">
        <a class="navbar-brand fs-4" href="index.php">Bean <span style="color: var(--coffee-gold);">&</span> Brew</a>
        <div class="offcanvas offcanvas-lg offcanvas-end site-navigation" tabindex="-1" id="siteNavigation" aria-labelledby="siteNavigationLabel">
            <div class="offcanvas-header d-lg-none">
                <h2 class="offcanvas-title fs-5" id="siteNavigationLabel">Navigation</h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close navigation"></button>
            </div>
            <div class="offcanvas-body p-0">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <a class="nav-link" href="index.php">Home</a>
                <a class="nav-link" href="menu.php">Menu</a>
                <a class="nav-link" href="about.php">About</a>
                <a class="nav-link" href="contact.php">Contact</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (($_SESSION['user_role'] ?? 'user') === 'admin'): ?>
                        <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                    <?php endif; ?>
                    <span class="nav-link text-white-50">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a class="nav-link" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                    <a class="btn btn-sm btn-warning rounded-pill px-3" href="register.php">Join us</a>
                <?php endif; ?>
            </div>
            </div>
        </div>
        <div class="navbar-tools d-flex align-items-center gap-2">
            <a class="cart-link" href="cart.php" aria-label="Shopping cart, <?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?> items">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 4h2l2.2 11.1a2 2 0 0 0 2 1.6h8.7a2 2 0 0 0 1.9-1.4L22 8H6"></path>
                    <circle cx="10" cy="20" r="1"></circle>
                    <circle cx="18" cy="20" r="1"></circle>
                </svg>
                <!-- <span>Cart</span> -->
                <span class="cart-count" data-cart-count><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#siteNavigation" aria-controls="siteNavigation" aria-label="Open navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</nav>
<main class="<?php echo basename($_SERVER['SCRIPT_NAME'] ?? '') === 'index.php' ? 'home-page' : ''; ?>">
