<?php
require_once "../includes/config.php";
requireAdmin();

$menu_count = (int)$pdo->query("SELECT COUNT(*) FROM coffee_items")->fetchColumn();
$order_count = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

$page_title = "Admin Dashboard | Bean & Brew";
include "../includes/header.php";
?>

<div class="container">
    <h2 style="color: var(--primary-color);">Admin Dashboard</h2>
    <p>Manage the shop from one place.</p>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin: 30px 0;">
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0;">Menu Items</h3>
            <p style="font-size: 2rem; margin: 0; color: var(--secondary-color);"><?php echo $menu_count; ?></p>
        </div>
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0;">Orders</h3>
            <p style="font-size: 2rem; margin: 0; color: var(--secondary-color);"><?php echo $order_count; ?></p>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0;">Menu Management</h3>
        <p>Add new coffees, edit prices and descriptions, or remove items.</p>
        <a href="admin_menu.php" style="display: inline-block; background: var(--primary-color); color: white; padding: 10px 18px; border-radius: 5px; text-decoration: none; font-weight: bold;">Manage Menu</a>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
