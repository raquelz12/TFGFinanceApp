<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

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

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("
            SELECT id FROM categories 
            WHERE name = 'Sin categoría' AND user_id IS NULL
            LIMIT 1
        ");
        $stmt->execute();
        $defaultCategory = $stmt->fetchColumn();

        $stmt = $pdo->prepare("
            UPDATE expenses
            SET category_id = ?
            WHERE category_id = ? AND user_id = ?
        ");
        $stmt->execute([$defaultCategory, $cat_id, $user_id]);

        $stmt = $pdo->prepare("
            DELETE FROM categories
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$cat_id, $user_id]);

        $pdo->commit();
        $_SESSION['message'] = 'Categoría eliminada correctamente';

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = 'Error al eliminar la categoría';
    }

    header('Location: ../../categories.php');
    exit;
}

header('Location: ../../categories.php');
exit;
