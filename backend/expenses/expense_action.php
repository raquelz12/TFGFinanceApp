<?php
session_start();
require '../config/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../expenses.php");
    exit;
}

$user_id     = $_SESSION['user_id'];
$name        = trim($_POST['name'] ?? '');
$amount      = floatval($_POST['amount'] ?? 0);
$category_id = intval($_POST['category_id'] ?? 0);

if ($name === '' || $amount <= 0 || $category_id <= 0) {
    $_SESSION['error'] = 'Datos del gasto no válidos';
    header("Location: ../../expenses.php");
    exit;
}

$pdo->beginTransaction();

try {
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

    $pdo->commit();
    $_SESSION['success'] = 'Gasto añadido correctamente';

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = 'Error al guardar el gasto';
}

header("Location: ../../expenses.php");
exit;
