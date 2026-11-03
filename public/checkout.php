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

// Pre-fill information if user is logged in
$prefill_name = "";
$prefill_email = "";

if (isset($_SESSION['user_id'])) {
    $prefill_name = $_SESSION['user_name'];
    $prefill_email = $_SESSION['user_email'];
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

<section class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7 col-xl-6">
        <h1 class="h2 text-center mb-4">Complete Your Order</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-lg-5">
            <h2 class="h4 mb-3">Order Summary</h2>
            <div class="border-bottom pb-3 mb-4">
                <?php foreach ($cart_items as $item): ?>
                    <div class="d-flex justify-content-between gap-3 mb-2">
                        <span><?php echo htmlspecialchars($item['name']); ?> <span class="text-secondary">(x<?php echo $item['quantity']; ?>)</span></span>
                        <span class="text-nowrap">$<?php echo number_format($item['subtotal'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
                    <span>Total</span>
                    <span>$<?php echo number_format($total_price, 2); ?></span>
                </div>
            </div>

            <form action="checkout.php" method="POST" class="vstack gap-3">
                <div>
                    <label for="name" class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" autocomplete="name" required placeholder="John Doe" value="<?php echo htmlspecialchars($prefill_name); ?>">
                </div>
                <div>
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" autocomplete="email" required placeholder="john@example.com" value="<?php echo htmlspecialchars($prefill_email); ?>">
                </div>
                <button type="submit" class="btn btn-warning btn-lg rounded-pill fw-semibold w-100">Place Order</button>
            </form>
            </div>
        </div>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
