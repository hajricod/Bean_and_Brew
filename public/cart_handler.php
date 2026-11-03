<?php
require_once "../includes/config.php";

function cartResponse($success, $message, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'cartCount' => array_sum($_SESSION['cart'] ?? []),
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $item_id = $_POST['item_id'] ?? null;
    $is_ajax = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        if ($is_ajax) {
            cartResponse(false, 'Your session expired. Refresh the page and try again.', 403);
        }
        http_response_code(403);
        exit('Invalid request token.');
    }

    if ($action === 'add' && $item_id !== null) {
        $item_id = filter_var($item_id, FILTER_VALIDATE_INT);
        if (!$item_id) {
            if ($is_ajax) {
                cartResponse(false, 'Invalid menu item.', 400);
            }
            http_response_code(400);
            exit('Invalid menu item.');
        }

        $stmt = $pdo->prepare("SELECT id, name FROM coffee_items WHERE id = ?");
        $stmt->execute([$item_id]);
        $item = $stmt->fetch();
        if (!$item) {
            if ($is_ajax) {
                cartResponse(false, 'That menu item is no longer available.', 404);
            }
            http_response_code(404);
            exit('Menu item not found.');
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][$item_id] = ($_SESSION['cart'][$item_id] ?? 0) + 1;

        if ($is_ajax) {
            cartResponse(true, htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') . ' added to your cart.');
        }

        $redirect = $_POST['redirect'] ?? 'menu.php';
        header("Location: " . (in_array($redirect, ['index.php', 'menu.php', 'product.php'], true) ? $redirect : 'menu.php'));
        exit;
    }

    if ($action === 'remove' && $item_id !== null) {
        if (isset($_SESSION['cart'][$item_id])) {
            unset($_SESSION['cart'][$item_id]);
        }
        header("Location: cart.php");
        exit;
    }

    if ($action === 'clear') {
        unset($_SESSION['cart']);
        header("Location: cart.php");
        exit;
    }
}

header("Location: index.php");
exit;
