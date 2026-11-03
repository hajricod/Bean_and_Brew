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
        main { flex: 1; }
        .site-nav { background: rgba(43, 33, 29, .97); }
        .site-nav .navbar-brand { color: #fff; font-weight: 750; letter-spacing: -.03em; }
        .site-nav .nav-link { color: rgba(255,255,255,.82); }
        .site-nav .nav-link:hover, .site-nav .nav-link:focus { color: var(--coffee-gold); }
        .cart-link { border: 1px solid rgba(255,255,255,.26); border-radius: 999px; padding-inline: 1rem !important; }
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavigation" aria-controls="siteNavigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="siteNavigation">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <a class="nav-link" href="index.php">Home</a>
                <a class="nav-link" href="menu.php">Menu</a>
                <a class="nav-link" href="about.php">About</a>
                <a class="nav-link" href="contact.php">Contact</a>
                <a class="nav-link cart-link" href="cart.php">Cart <span class="badge rounded-pill text-bg-light" data-cart-count><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span></a>
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
</nav>
<main>
