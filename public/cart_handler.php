<?php
require_once "../includes/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $item_id = $_POST['item_id'] ?? null;

    if ($action === 'add' && $item_id !== null) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if item already in cart
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id]++;
        } else {
            $_SESSION['cart'][$item_id] = 1;
        }
        
        header("Location: " . ($_POST['redirect'] ?? 'menu.php'));
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
