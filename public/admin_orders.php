<?php
require_once "../includes/config.php";
requireAdmin();

// Fetch all orders
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();

$page_title = "Order Management | Bean & Brew";
include "../includes/header.php";
?>

<section class="container py-4 py-lg-5">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3 mb-4">
        <div>
            <p class="text-uppercase small fw-semibold text-secondary mb-2">Shop management</p>
            <h1 class="display-6 fw-bold mb-2">Order Management</h1>
            <p class="text-secondary mb-0">View and update the status of customer orders.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-secondary">No orders found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo date('M j, Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                                <div class="small text-secondary"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                            </td>
                            <td class="fw-bold">$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <?php
                                    $statusClass = 'badge bg-secondary';
                                    switch ($order['status']) {
                                        case 'pending': $statusClass = 'badge bg-warning text-dark'; break;
                                        case 'preparing': $statusClass = 'badge bg-info text-dark'; break;
                                        case 'completed': $statusClass = 'badge bg-success'; break;
                                        case 'cancelled': $statusClass = 'badge bg-danger'; break;
                                    }
                                ?>
                                <span class="<?php echo $statusClass; ?>"><?php echo ucfirst($order['status']); ?></span>
                            </td>
                            <td>
                                <form action="admin_order_status.php" method="POST" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="new_status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                        <option value="" disabled selected>Update</option>
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="preparing" <?php echo $order['status'] === 'preparing' ? 'selected' : ''; ?>>Preparing</option>
                                        <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include "../includes/footer.php"; ?>
