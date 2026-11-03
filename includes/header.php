<?php
// header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Bean & Brew | Artisanal Coffee'; ?></title>
    <style>
        :root {
            --primary-color: #4e342e;
            --secondary-color: #795548;
            --accent-color: #d7ccc8;
            --text-color: #3e2723;
            --bg-color: #fafafa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .cart-link {
            background-color: var(--secondary-color);
            padding: 5px 15px;
            border-radius: 20px;
            transition: background 0.3s;
        }

        .cart-link:hover {
            background-color: var(--accent-color);
            color: var(--primary-color);
            text-decoration: none;
        }

        .user-nav {
            margin-left: 20px;
            padding-left: 20px;
            border-left: 1px solid rgba(255,255,255,0.3);
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<header>
    <h1 style="margin: 0;">Bean & Brew</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="cart.php" class="cart-link">
            Cart (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-nav">
                <span style="font-size: 0.9rem; opacity: 0.8;">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <?php if (($_SESSION['user_role'] ?? 'user') === 'admin'): ?>
                    <a href="admin_dashboard.php" style="margin-left: 10px; font-size: 0.9rem;">Admin Dashboard</a>
                <?php endif; ?>
                <a href="logout.php" style="margin-left: 10px; font-size: 0.9rem;">Logout</a>
            </div>
        <?php else: ?>
            <div class="user-nav">
                <a href="login.php" style="font-size: 0.9rem;">Login</a>
                <a href="register.php" style="font-size: 0.9rem; margin-left: 10px;">Register</a>
            </div>
        <?php endif; ?>
    </nav>
</header>
