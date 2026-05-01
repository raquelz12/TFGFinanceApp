<?php
session_start();
require_once __DIR__ . '/../../bootstrap.php';

$redirect = $_SERVER['HTTP_REFERER'] ?? '../../expenses.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $redirect");
    exit;
}

$user_id = $_SESSION['user_id'];
$action  = $_POST['action'] ?? '';

if ($action === 'add') {

    $name        = trim($_POST['name'] ?? '');
    $amount      = floatval($_POST['amount'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);

    if ($name === '') {
        $_SESSION['message'] = 'El nombre no puede estar vacío';
        header("Location: $redirect");
        exit;
    }

    if ($amount <= 0) {
        $_SESSION['message'] = 'La cantidad debe ser mayor que cero';
        header("Location: $redirect");
        exit;
    }

    if ($category_id <= 0) {
        $_SESSION['message'] = 'Categoría inválida';
        header("Location: $redirect");
        exit;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO expenses (user_id, category_id, name, amount)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $category_id, $name, $amount]);

        $stmt = $pdo->prepare("
            UPDATE categories
            SET completed_amount = completed_amount + ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$amount, $category_id, $user_id]);

        $pdo->commit();
        $_SESSION['message'] = 'Gasto añadido correctamente';

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = 'Error al guardar el gasto';
    }

    header("Location: $redirect");
    exit;
}

if ($action === 'edit') {

    $expense_id = intval($_POST['expense_id'] ?? 0);
    $name       = trim($_POST['name'] ?? '');
    $amount     = floatval($_POST['amount'] ?? 0);

    if ($expense_id <= 0 || $name === '' || $amount <= 0) {
        $_SESSION['message'] = 'Datos inválidos';
        header("Location: $redirect");
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT amount, category_id
        FROM expenses
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$expense_id, $user_id]);
    $old = $stmt->fetch();

    if (!$old) {
        $_SESSION['message'] = 'Gasto no encontrado';
        header("Location: $redirect");
        exit;
    }

    $diff = $amount - $old['amount'];

    try {

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("
            UPDATE expenses
            SET name = ?, amount = ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$name, $amount, $expense_id, $user_id]);

        $stmt = $pdo->prepare("
            UPDATE categories
            SET completed_amount = completed_amount + ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$diff, $old['category_id'], $user_id]);

        $pdo->commit();
        $_SESSION['message'] = 'Gasto actualizado';

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = 'Error al actualizar';
    }

    header("Location: $redirect");
    exit;
}

if ($action === 'delete') {
    $expense_id = intval($_POST['expense_id'] ?? 0);
    if ($expense_id <= 0) {
        $_SESSION['message'] = 'ID inválido';
        header("Location: $redirect");
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT amount, category_id
        FROM expenses
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$expense_id, $user_id]);
    $expense = $stmt->fetch();

    if (!$expense) {
        $_SESSION['message'] = 'Gasto no encontrado';
        header("Location: $redirect");
        exit;
    }

    try {

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("
            DELETE FROM expenses
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$expense_id, $user_id]);

        $stmt = $pdo->prepare("
            UPDATE categories
            SET completed_amount = completed_amount - ?
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$expense['amount'], $expense['category_id'], $user_id]);

        $pdo->commit();
        $_SESSION['message'] = 'Gasto eliminado';

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['message'] = 'Error al eliminar el gasto';
    }

    header("Location: $redirect");
    exit;
}

$_SESSION['message'] = 'Acción no válida';
header("Location: $redirect");
exit;
