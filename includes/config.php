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

function getCoffeeItems($pdo) {
    $stmt = $pdo->query("SELECT id, name, description, price, image FROM coffee_items");
    return $stmt->fetchAll();
}

$coffee_items = getCoffeeItems($pdo);
