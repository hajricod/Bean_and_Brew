<?php
// public/checkout.php
require_once "../includes/config.php";

// If no cart, redirect to menu
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: menu.php");
    exit;
}

$cart_items = [];
$total_price = 0;

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $pdo->beginTransaction();

            // 1. Insert Order
            $stmt = $pdo->prepare("INSERT INTO orders (customer_name, customer_email, total_amount) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $total_price]);
            $order_id = $pdo->lastInsertId();

            // 2. Insert Order Items
            $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_time) VALUES (?, ?, ?, ?)");
            foreach ($cart_items as $item) {
                $stmtItem->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
            }

            $pdo->commit();

            // 3. Clear Cart
            unset($_SESSION['cart']);

            // 4. Redirect to success page
            header("Location: order_success.php?order_id=" . $order_id);
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Transaction failed: " . $e->getMessage();
        }
    } else {
        $error = "Please provide a valid name and email address.";
    }
}

$page_title = "Checkout | Bean & Brew";
include "../includes/header.php";
?>

<div class="container">
    <div style="max-width: 600px; margin: 0 auto;">
        <h2 style="text-align: center;">Complete Your Order</h2>
        
        <?php if (isset($error)): ?>
            <div style="background: #ffcdd2; color: #c62828; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0;">Order Summary</h3>
            <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                <?php foreach ($cart_items as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span>
                        <span>$<?php echo number_format($item['subtotal'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; margin-top: 10px;">
                    <span>Total</span>
                    <span>$<?php echo number_format($total_price, 2); ?></span>
                </div>
            </div>

            <form action="checkout.php" method="POST">
                <div style="margin-bottom: 15px;">
                    <label for="name" style="display: block; font-weight: bold; margin-bottom: 5px;">Full Name</label>
                    <input type="text" name="name" id="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="John Doe">
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="email" style="display: block; font-weight: bold; margin-bottom: 5px;">Email Address</label>
                    <input type="email" name="email" id="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="john@example.com">
                </div>
                <button type="submit" style="width: 100%; background-color: #4e342e; color: white; padding: 15px; border: none; border-radius: 5px; font-size: 1.1rem; font-weight: bold; cursor: pointer;">Place Order</button>
            </form>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
