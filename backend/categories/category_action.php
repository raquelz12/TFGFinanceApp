<?php
session_start();
require '../config/connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'insert') {
    $category_id = (int) $_POST['category_id'];
    $amount = (float) $_POST['amount'];

    if ($category_id <= 0 || $amount <= 0) {
        $_SESSION['message'] = 'Datos inválidos para la categoría';
        header('Location: ../../categories.php');
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO categories (user_id, name, amount, completed_amount)
        SELECT ?, name, ?, 0
        FROM categories
        WHERE id = ? AND user_id IS NULL
    ");
    $stmt->execute([$user_id, $amount, $category_id]);

    $_SESSION['message'] = 'Categoría añadida correctamente';
}

if ($action === 'update') {
    $cat_id = (int) $_POST['id'];
    $amount = (float) $_POST['amount'];

    if ($amount <= 0) {
        $_SESSION['message'] = 'Cantidad inválida';
        header('Location: ../../categories.php');
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE categories
        SET amount = ?
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$amount, $cat_id, $user_id]);

    $_SESSION['message'] = 'Límite de categoría actualizado';
}

if ($action === 'delete') {
    $cat_id = (int) $_POST['id'];

    $stmt = $pdo->prepare("
        DELETE FROM categories
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$cat_id, $user_id]);

    $_SESSION['message'] = 'Categoría eliminada correctamente';
}

header('Location: ../../categories.php');
exit;
