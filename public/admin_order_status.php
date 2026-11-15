<?php
require_once "../includes/config.php";
requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid CSRF token.']);
        exit;
    }

    $order_id = $_POST['order_id'] ?? null;
    $new_status = $_POST['new_status'] ?? null;

    $allowed_statuses = ['pending', 'preparing', 'completed', 'cancelled'];

    if ($order_id && in_array($new_status, $allowed_statuses)) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $order_id]);
            
            echo json_encode(['success' => true]);
            exit;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error updating status: ' . $e->getMessage()]);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request.']);
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}
