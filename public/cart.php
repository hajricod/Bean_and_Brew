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

<div class="container">
    <h2 style="text-align: center; margin-bottom: 30px;">Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div style="text-align: center; padding: 40px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <p style="font-size: 1.2rem; color: #666;">Your cart is currently empty.</p>
            <a href="menu.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #4e342e; color: white; text-decoration: none; border-radius: 5px;">Browse Menu</a>
        </div>
    <?php else: ?>
        <div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                <thead style="border-bottom: 2px solid #eee;">
                    <tr style="text-align: left;">
                        <th style="padding: 15px 0;">Item</th>
                        <th style="padding: 15px 0;">Price</th>
                        <th style="padding: 15px 0;">Quantity</th>
                        <th style="padding: 15px 0;">Subtotal</th>
                        <th style="padding: 15px 0; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px 0;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <span style="font-size: 1.5rem;"><?php echo $item['image']; ?></span>
                                    <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                </div>
                            </td>
                            <td style="padding: 15px 0;">$<?php echo number_format($item['price'], 2); ?></td>
                            <td style="padding: 15px 0;"><?php echo $item['quantity']; ?></td>
                            <td style="padding: 15px 0;">$<?php echo number_format($item['subtotal'], 2); ?></td>
                            <td style="padding: 15px 0; text-align: right;">
                                <form action="cart_handler.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" style="background: none; border: none; color: #e53935; cursor: pointer; font-size: 0.9rem;">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: right;">
                <h3 style="font-size: 1.5rem;">Total: <span style="color: #4e342e;">$<?php echo number_format($total_price, 2); ?></span></h3>
                
                <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
                    <form action="cart_handler.php" method="POST">
                        <input type="hidden" name="action" value="clear">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" style="background: none; border: 1px solid #ccc; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Clear Cart</button>
                    </form>
                    <a href="checkout.php" style="display: inline-block; background-color: #4e342e; color: white; padding: 10px 30px; border-radius: 5px; text-decoration: none; font-weight: bold;">Checkout</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include "../includes/footer.php"; ?>
