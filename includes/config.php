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
    $stmt = $pdo->query("SELECT id, name, description, price, image, category, ingredients, calories FROM coffee_items ORDER BY category, name");
    return $stmt->fetchAll();
}

function hasLocalCatalogImage($filename) {
    return is_string($filename)
        && preg_match('/\A[a-zA-Z0-9._-]+\.(?:jpe?g|png|webp|gif)\z/i', $filename) === 1
        && is_file(__DIR__ . '/../public/assets/images/' . $filename);
}

function catalogImageUrl($filename) {
    if (hasLocalCatalogImage($filename)) {
        return 'assets/images/' . rawurlencode($filename);
    }

    return 'assets/images/product-placeholder.svg';
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
