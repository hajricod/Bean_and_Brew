<?php
require_once "../includes/config.php";
$page_title = "Your Cart | Bean & Brew";
include "../includes/header.php";

$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $stmt = $pdo->prepare("SELECT * FROM coffee_items WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        if ($item) {
            $item['quantity'] = $quantity;
            $item['subtotal'] = $item['price'] * $quantity;
            $cart_items[] = $item;
            $total_price += $item['subtotal'];
        }
    }
}
?>

<section class="container py-4 py-lg-5">
    <h1 class="h2 text-center mb-4">Your Shopping Cart</h1>

    <?php if (empty($cart_items)): ?>
        <div class="card border-0 shadow-sm rounded-4 text-center">
            <div class="card-body p-4 p-md-5">
            <p class="lead text-secondary">Your cart is currently empty.</p>
            <a href="menu.php" class="btn btn-warning rounded-pill px-4 fw-semibold">Browse Menu</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3 p-md-4">
            <div class="table-responsive">
            <table class="table align-middle mb-4">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?php echo htmlspecialchars(catalogImageUrl($item['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="" width="48" height="48" class="rounded object-fit-cover">
                                    <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                </div>
                            </td>
                            <td class="text-nowrap">$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td class="text-nowrap">$<?php echo number_format($item['subtotal'], 2); ?></td>
                            <td class="text-end">
                                <form action="cart_handler.php" method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <div class="text-end">
                <h2 class="h4">Total: <span class="text-dark">$<?php echo number_format($total_price, 2); ?></span></h2>
                
                <div class="d-flex flex-column flex-sm-row justify-content-sm-end gap-2 mt-4">
                    <form action="cart_handler.php" method="POST">
                        <input type="hidden" name="action" value="clear">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="btn btn-outline-secondary w-100">Clear Cart</button>
                    </form>
                    <a href="checkout.php" class="btn btn-warning rounded-pill px-4 fw-semibold">Checkout</a>
                </div>
            </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php include "../includes/footer.php"; ?>
