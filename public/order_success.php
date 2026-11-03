<?php
require_once "../includes/config.php";
$page_title = "Order Confirmed | Bean & Brew";
include "../includes/header.php";

$order_id = $_GET['order_id'] ?? null;
$order = null;

if ($order_id) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
}
?>

<div class="container" style="text-align: center;">
    <div style="background: white; padding: 50px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div role="img" aria-label="Order successful" style="color: #4caf50; margin-bottom: 20px;">
            <svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="48" cy="48" r="44" stroke="currentColor" stroke-width="4"/>
                <path d="M27 49L41 63L69 34" stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1 style="color: #4e342e;">Thank You for Your Order!</h1>
        
        <?php if ($order): ?>
            <p style="font-size: 1.2rem; color: #666;">Your order <strong>#<?php echo $order['id']; ?></strong> has been placed successfully.</p>
            <p>A confirmation email has been sent to <strong><?php echo htmlspecialchars($order['customer_email']); ?></strong>.</p>
            
            <div style="margin-top: 30px; padding: 20px; background: #fafafa; border-radius: 5px; display: inline-block; text-align: left;">
                <p style="margin: 0;"><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p style="margin: 5px 0 0 0;"><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
                <p style="margin: 5px 0 0 0;"><strong>Order Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></p>
            </div>
        <?php else: ?>
            <p>Order details could not be found. Please contact support.</p>
        <?php endif; ?>

        <div style="margin-top: 40px;">
            <a href="index.php" style="display: inline-block; padding: 12px 25px; background-color: #4e342e; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">Back to Home</a>
        </div>
    </div>
</div>

<?php include "../includes/footer.php"; ?>
