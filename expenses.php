<?php
session_start();
require 'backend/config/connection.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        e.id,
        e.name,
        e.amount AS amount,
        e.created_at AS created_at,
        c.name AS category
    FROM expenses e
    JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ?
    ORDER BY e.created_at DESC
");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll();

include 'includes/header.php'; ?>

<main class="expenses-page">
    <section class="expenses-hero">
        <h2>Gastos</h2>
        <p>Visualiza todos tus gastos</p>
    </section>
    <section class="transactions-section">
            <button type="button" class="button-app" data-bs-toggle="modal" data-bs-target="#ModalAddExpense">
                + Añadir Gasto
            </button>
        <div class="transaction-list">
            <div class="transaction-item transaction-header">
                <span>Gasto</span>
                <span>Categoría</span>
                <span>Importe</span>
                <span>Fecha</span>
            </div>
            <?php foreach ($expenses as $exp): ?>
            <div class="transaction-item">
                <span><?= htmlspecialchars($exp['name']) ?></span>
                <span><?= htmlspecialchars($exp['category']) ?></span>
                <span><?= number_format($exp['amount'],2,',','.') ?> €</span>
                <span><?= htmlspecialchars($exp['created_at']) ?></span>
            </div>
        <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>