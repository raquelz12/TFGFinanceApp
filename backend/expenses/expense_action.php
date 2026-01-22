<?php
session_start();
require '../config/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../app_dashboard.php");
    exit;
}

$user_id     = $_SESSION['user_id'];
$name        = trim($_POST['name'] ?? '');
$amount      = floatval($_POST['amount'] ?? 0);
$category_id = intval($_POST['category_id'] ?? 0);

if ($name === '' || $amount <= 0 || $category_id <= 0) {
    $_SESSION['error'] = 'Datos del gasto no válidos';
    header("Location: ../../app_dashboard.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT id
    FROM categories
    WHERE id = ? AND user_id = ?
");
$stmt->execute([$category_id, $user_id]);

if (!$stmt->fetch()) {
    $_SESSION['error'] = 'Categoría no válida';
    header("Location: ../../app_dashboard.php");
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO expenses (user_id, category_id, name, amount)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$user_id, $category_id, $name, $amount]);

$stmt = $pdo->prepare("
    UPDATE categories
    SET 
        amount = amount + ?,
        completed_amount = completed_amount + ?
    WHERE id = ? AND user_id = ?
");
$stmt->execute([$amount, $amount, $category_id, $user_id]);

$_SESSION['success'] = 'Gasto añadido correctamente';
header("Location: ../../app_dashboard.php");
exit;
