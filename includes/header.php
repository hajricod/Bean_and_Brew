<?php
// header.php
$current_page = basename($_SERVER['SCRIPT_NAME'] ?? '');
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
        .navbar-toggler { width: 40px; height: 40px; padding: 0; margin: .25rem; display: grid; place-items: center; border-radius: 50%; }
        .cart-link { display: inline-flex; align-items: center; gap: .45rem; color: var(--coffee-ink) !important; background: var(--coffee-gold); border: 1px solid var(--coffee-gold); border-radius: 999px; padding: .45rem .8rem !important; font-size: .9rem; font-weight: 700; line-height: 1; text-decoration: none; transition: background-color .2s ease, border-color .2s ease, transform .2s ease; }
        .cart-link:hover, .cart-link:focus { color: var(--coffee-ink) !important; background: #e6bb86; border-color: #e6bb86; transform: translateY(-1px); }
        .cart-link svg { width: 1.1rem; height: 1.1rem; }
        .cart-count { min-width: 1.25rem; height: 1.25rem; display: inline-grid; place-items: center; padding: 0 .25rem; border-radius: 999px; background: var(--coffee-ink); color: #fff; font-size: .7rem; }
        .site-navigation { --bs-offcanvas-width: min(85vw, 360px); background: var(--coffee-ink); }
        .site-navigation .offcanvas-title { color: #fff; font-weight: 750; }
        .site-navigation .offcanvas-body { display: flex; flex-direction: column; }
        .account-menu { flex-shrink: 0; }
        .account-toggle { display: flex; align-items: center; gap: .75rem; width: 100%; color: var(--coffee-gold); text-align: left; }
        .account-toggle:hover, .account-toggle:focus { color: var(--coffee-gold); }
        .account-avatar { width: 2.5rem; height: 2.5rem; flex: 0 0 2.5rem; display: grid; place-items: center; border-radius: 50%; background: var(--coffee-gold); color: var(--coffee-ink); font-weight: 700; }
        .account-copy { display: grid; min-width: 0; }
        .account-name { font-weight: 600; }
        .account-email { overflow: hidden; color: rgba(255,255,255,.6); font-size: .8rem; text-overflow: ellipsis; }
        .account-menu .dropdown-menu { background-color: var(--coffee-gold); }
        .account-menu .dropdown-item:hover,
        .account-menu .dropdown-item:focus,
        .account-menu .dropdown-item:active,
        .account-menu .dropdown-item.active { color: var(--coffee-gold); background-color: var(--coffee-ink); }
        @media (max-width: 991.98px) {
            .site-navigation .offcanvas-header { min-height: 76px; padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,.12); }
            .site-navigation .offcanvas-body { height: calc(100% - 76px); min-height: 0; overflow-y: auto; }
            .site-navigation .navbar-nav { width: 100%; padding: 1rem; }
            .site-navigation .navbar-nav .nav-link { padding: .9rem 1rem; border: 0; border-radius: .75rem; color: rgba(255,255,255,.82); font-weight: 500; }
            .site-navigation .navbar-nav .nav-link:hover, .site-navigation .navbar-nav .nav-link:focus { color: #fff; background: rgba(255,255,255,.08); }
            .site-navigation .navbar-nav .nav-link.active,
            .site-navigation .navbar-nav .nav-link.active:hover,
            .site-navigation .navbar-nav .nav-link.active:focus { color: var(--coffee-ink); background: var(--coffee-gold); }
            .site-navigation .navbar-nav.ms-auto { margin-left: 0 !important; }
            .account-menu { position: relative; margin-top: auto; padding: 1rem 1.25rem calc(1.25rem + env(safe-area-inset-bottom)); border-top: 1px solid rgba(255,255,255,.14); background: rgba(255,255,255,.04); }
            .account-toggle { min-height: 3.5rem; padding: .5rem; border-radius: .75rem; }
            .account-toggle:hover, .account-toggle:focus { background: rgba(255,255,255,.08); }
            .account-menu .dropdown-menu { width: 100%; margin-bottom: .5rem; }
            .account-menu.dropup .dropdown-menu { top: auto; bottom: 100%; }
        }
        @media (min-width: 992px) {
            .site-navigation .offcanvas-body { flex-direction: row; align-items: center; }
            .site-navigation .navbar-nav .nav-link.active,
            .site-navigation .navbar-nav .nav-link.active:hover,
            .site-navigation .navbar-nav .nav-link.active:focus { color: var(--coffee-gold); background: transparent; }
            .account-menu { margin-left: .5rem; margin-right: .5rem; }
            .account-toggle { padding: .35rem .5rem; border-radius: 999px; }
            .account-avatar { width: 2rem; height: 2rem; flex-basis: 2rem; }
            .account-email { display: none; }
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
        <div class="offcanvas offcanvas-lg offcanvas-end site-navigation" tabindex="-1" id="siteNavigation" aria-label="Site navigation">
            <div class="offcanvas-header d-lg-none">
                <div>
                    <a class="navbar-brand fs-5 text-decoration-none" href="index.php">Bean <span style="color: var(--coffee-gold);">&</span> Brew</a>
                    <div class="small text-white-50">Explore the shop</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close navigation"></button>
            </div>
            <div class="offcanvas-body p-0">
                <div class="navbar-nav nav-pills ms-auto align-items-lg-center gap-lg-2">
                    <a class="nav-link<?php echo $current_page === 'index.php' ? ' active' : ''; ?>" href="index.php"<?php echo $current_page === 'index.php' ? ' aria-current="page"' : ''; ?>>Home</a>
                    <a class="nav-link<?php echo $current_page === 'menu.php' ? ' active' : ''; ?>" href="menu.php"<?php echo $current_page === 'menu.php' ? ' aria-current="page"' : ''; ?>>Menu</a>
                    <a class="nav-link<?php echo $current_page === 'about.php' ? ' active' : ''; ?>" href="about.php"<?php echo $current_page === 'about.php' ? ' aria-current="page"' : ''; ?>>About</a>
                    <a class="nav-link<?php echo $current_page === 'contact.php' ? ' active' : ''; ?>" href="contact.php"<?php echo $current_page === 'contact.php' ? ' aria-current="page"' : ''; ?>>Contact</a>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a class="nav-link" href="login.php">Login</a>
                    <?php endif; ?>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php $account_name = $_SESSION['user_name'] ?? 'Account'; ?>
                    <div class="dropdown account-menu">
                        <button class="account-toggle btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="account-avatar" aria-hidden="true"><?php echo htmlspecialchars(strtoupper(substr($account_name, 0, 1)), ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="account-copy">
                                <span class="account-name">Hi, <?php echo htmlspecialchars($account_name, ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="account-email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (($_SESSION['user_role'] ?? 'user') === 'admin'): ?>
                                <li><a class="dropdown-item" href="admin_dashboard.php">Admin dashboard</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
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
<main class="<?php echo $current_page === 'index.php' ? 'home-page' : ''; ?>">
