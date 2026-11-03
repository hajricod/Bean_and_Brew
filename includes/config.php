<?php
// includes/config.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_path = __DIR__ . '/database.sqlite';

try {
    $pdo = new PDO("sqlite:$db_path");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT name, email, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $current_user = $stmt->fetch();

    if ($current_user) {
        $_SESSION['user_name'] = $current_user['name'];
        $_SESSION['user_email'] = $current_user['email'];
        $_SESSION['user_role'] = $current_user['role'] ?? 'user';
    } else {
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email'], $_SESSION['user_role']);
    }
}

function getCoffeeItems($pdo) {
    $stmt = $pdo->query("SELECT id, name, description, price, image FROM coffee_items");
    return $stmt->fetchAll();
}

function requireAdmin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    if (($_SESSION['user_role'] ?? 'user') !== 'admin') {
        http_response_code(403);
        exit('Access denied. Admin privileges are required.');
    }
}

$coffee_items = getCoffeeItems($pdo);
