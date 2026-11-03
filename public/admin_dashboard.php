<?php
require_once "../includes/config.php";
requireAdmin();

$menu_count = (int)$pdo->query("SELECT COUNT(*) FROM coffee_items")->fetchColumn();
$order_count = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

$page_title = "Admin Dashboard | Bean & Brew";
include "../includes/header.php";
?>

<section class="container py-4 py-lg-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-semibold text-secondary mb-2">Shop management</p>
            <h1 class="display-6 fw-bold mb-2">Admin Dashboard</h1>
            <p class="text-secondary mb-0">Manage the shop from one place.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <article class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h5 card-title text-secondary">Menu Items</h2>
                    <p class="display-4 fw-semibold mb-0"><?php echo $menu_count; ?></p>
                </div>
            </article>
        </div>
        <div class="col-md-6">
            <article class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h5 card-title text-secondary">Orders</h2>
                    <p class="display-4 fw-semibold mb-0"><?php echo $order_count; ?></p>
                </div>
            </article>
        </div>
    </div>

    <article class="card border-0 shadow-sm rounded-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-4 p-lg-5">
            <div>
                <h2 class="h4 card-title">Menu Management</h2>
                <p class="card-text text-secondary mb-0">Add new coffees, edit prices and descriptions, or remove items.</p>
            </div>
            <a href="admin_menu.php" class="btn btn-warning rounded-pill px-4 fw-semibold">Manage Menu</a>
        </div>
    </article>
</section>

<?php include "../includes/footer.php"; ?>
