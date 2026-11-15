<?php
require_once "../includes/config.php";
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        exit('Invalid CSRF token.');
    }

    $order_id = $_POST['order_id'] ?? null;
    $new_status = $_POST['new_status'] ?? null;

    $allowed_statuses = ['pending', 'preparing', 'completed', 'cancelled'];

    if ($order_id && in_array($new_status, $allowed_statuses)) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $order_id]);
            
            header("Location: admin_orders.php?success=1");
            exit;
        } catch (PDOException $e) {
            die("Error updating status: " . $e->getMessage());
        }
    } else {
        die("Invalid request.");
    }
} else {
    header("Location: admin_orders.php");
    exit;
}
?>
