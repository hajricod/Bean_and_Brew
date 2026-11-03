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

$item_count = array_sum(array_column($cart_items, 'quantity'));
?>

<section class="container py-4 py-lg-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3 mb-4 mb-lg-5">
        <div>
            <p class="text-uppercase small fw-semibold text-secondary mb-2">Your order</p>
            <h1 class="display-6 fw-bold mb-2">Shopping cart</h1>
            <p class="text-secondary mb-0">Review your favorites before checkout.</p>
        </div>
        <a href="menu.php" class="btn btn-outline-dark rounded-pill px-4">&larr; Continue shopping</a>
    </div>

    <?php if (empty($cart_items)): ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center p-4 p-md-5">
                <div class="d-inline-grid place-items-center rounded-circle bg-light text-secondary p-4 mb-3" aria-hidden="true">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 8h13v8a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8Z"/>
                        <path d="M17 10h1a3 3 0 1 1 0 6h-1M7 3v2m4-2v2m4-2v2"/>
                    </svg>
                </div>
                <h2 class="h3 fw-bold">Your cart is taking a coffee break</h2>
                <p class="text-secondary mb-4">Once you find something you love, it’ll be waiting here.</p>
                <a href="menu.php" class="btn btn-warning rounded-pill px-4 fw-semibold">Explore the menu</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4 g-xl-5 align-items-start">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 px-4 pt-4 pb-0">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <h2 class="h5 fw-bold mb-0">Your picks</h2>
                            <span class="badge rounded-pill text-bg-light border px-3 py-2"><?php echo $item_count; ?> <?php echo $item_count === 1 ? 'item' : 'items'; ?></span>
                        </div>
                    </div>
                    <div class="card-body px-4 pt-0 pb-2">
                        <div class="list-group list-group-flush">
                    <?php foreach ($cart_items as $item): ?>
                            <div class="list-group-item px-0 py-4">
                                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3 gap-md-4">
                                    <img src="<?php echo htmlspecialchars(catalogImageUrl($item['image']), ENT_QUOTES, 'UTF-8'); ?>" alt="" width="88" height="88" class="rounded-3 object-fit-cover flex-shrink-0">
                                    <div class="flex-grow-1 min-w-0">
                                        <h3 class="h5 fw-bold mb-1"><?php echo htmlspecialchars($item['name']); ?></h3>
                                        <p class="small text-secondary mb-2"><?php echo htmlspecialchars($item['category']); ?></p>
                                        <span class="badge rounded-pill text-bg-light border">Qty: <?php echo (int)$item['quantity']; ?></span>
                                    </div>
                                    <div class="d-flex flex-sm-column align-items-center align-items-sm-end justify-content-between gap-3">
                                        <div class="text-sm-end">
                                            <div class="fw-bold fs-5">$<?php echo number_format($item['subtotal'], 2); ?></div>
                                            <?php if ($item['quantity'] > 1): ?>
                                                <div class="small text-secondary">$<?php echo number_format($item['price'], 2); ?> each</div>
                                            <?php endif; ?>
                                        </div>
                                        <form action="cart_handler.php" method="POST">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3" aria-label="Remove <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?> from cart">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mt-3 text-secondary small">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span>Your items are saved in your cart while you browse.</span>
                </div>
            </div>

            <aside class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-4">Order summary</h2>
                        <div class="d-flex justify-content-between text-secondary mb-3">
                            <span>Items (<?php echo $item_count; ?>)</span>
                            <span>$<?php echo number_format($total_price, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3 mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="h4 fw-bold mb-0">$<?php echo number_format($total_price, 2); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-warning btn-lg rounded-pill w-100 fw-bold">Continue to checkout <span aria-hidden="true">&rarr;</span></a>
                        <p class="small text-secondary text-center mt-3 mb-0">Your total is confirmed before placing your order.</p>
                        <div class="border-top mt-4 pt-3">
                            <form action="cart_handler.php" method="POST">
                                <input type="hidden" name="action" value="clear">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="btn btn-link link-secondary text-decoration-none btn-sm px-0">Clear cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    <?php endif; ?>
</section>

<?php include "../includes/footer.php"; ?>
