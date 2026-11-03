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

<section class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
    <div class="card border-0 shadow-sm rounded-4 text-center">
        <div class="card-body p-4 p-md-5">
        <div role="img" aria-label="Order successful" class="text-success mb-3">
            <svg width="96" height="96" viewBox="0 0 96 96" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="48" cy="48" r="44" stroke="currentColor" stroke-width="4"/>
                <path d="M27 49L41 63L69 34" stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1 class="h2 text-dark">Thank You for Your Order!</h1>
        
        <?php if ($order): ?>
            <p class="lead text-secondary">Your order <strong>#<?php echo $order['id']; ?></strong> has been placed successfully.</p>
            <p>A confirmation email has been sent to <strong><?php echo htmlspecialchars($order['customer_email']); ?></strong>.</p>
            
            <div class="d-inline-block text-start bg-light rounded-3 p-3 p-md-4 mt-3">
                <p class="mb-1"><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p class="mb-1"><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
                <p class="mb-0"><strong>Order Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></p>
            </div>
        <?php else: ?>
            <p>Order details could not be found. Please contact support.</p>
        <?php endif; ?>

        <div class="mt-4">
            <a href="index.php" class="btn btn-warning rounded-pill px-4 fw-semibold">Back to Home</a>
        </div>
        </div>
    </div>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
