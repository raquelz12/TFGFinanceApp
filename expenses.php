<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM expenses 
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$totalExpenses = $stmt->fetchColumn();
$totalPages = ceil($totalExpenses / $limit);

$allowedSorts = [
    'name'     => 'e.name',
    'category' => 'c.name',
    'amount'   => 'e.amount',
    'date'     => 'e.created_at'
];

$sort  = $_GET['sort'] ?? 'date';
$order = $_GET['order'] ?? 'desc';

$order = strtolower($order) === 'asc' ? 'ASC' : 'DESC';
$orderBy = $allowedSorts[$sort] ?? 'e.created_at';

$stmt = $pdo->prepare("
    SELECT 
        e.id,
        e.name,
        e.amount,
        e.created_at,
        c.name AS category
    FROM expenses e
    JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = ?
    ORDER BY $orderBy $order
    LIMIT $limit OFFSET $offset
");
$stmt->execute([$user_id]);
$expenses = $stmt->fetchAll();

function sortArrow($column, $currentSort, $currentOrder) {
    if ($column !== $currentSort) return '';
    return $currentOrder === 'ASC' ? ' ↑' : ' ↓';
}

function sortLink($label, $column, $sort, $order, $page) {
    $newOrder = ($sort === $column && $order === 'ASC') ? 'desc' : 'asc';
    return "<a href='?sort=$column&order=$newOrder&page=$page'>$label</a>";
}

include 'includes/header.php';
?>

<main class="expenses-page">
    <section class="expenses-hero">
        <h2>Gastos</h2>
        <p>Visualiza todos tus gastos</p>
    </section>
    <section class="transactions-section">
        <button type="button" class="button-app" data-bs-toggle="modal" data-bs-target="#ModalAddExpense">
            + Añadir Gasto
        </button>
        <button onclick="window.location.href='backend/expenses/export.php'" class="button-app">
            Exportar Gastos CSV
        </button>
        <div class="transaction-list">
            <div class="transaction-item transaction-header">
                <span>
                    <?= sortLink('Gasto', 'name', $sort, $order, $page) ?>
                    <?= sortArrow('name', $sort, $order) ?>
                </span>
                <span>
                    <?= sortLink('Categoría', 'category', $sort, $order, $page) ?>
                    <?= sortArrow('category', $sort, $order) ?>
                </span>
                <span>
                    <?= sortLink('Importe', 'amount', $sort, $order, $page) ?>
                    <?= sortArrow('amount', $sort, $order) ?>
                </span>
                <span>
                    <?= sortLink('Fecha', 'date', $sort, $order, $page) ?>
                    <?= sortArrow('date', $sort, $order) ?>
                </span>
            </div>
            <?php foreach ($expenses as $exp): ?>
                <div class="transaction-item">
                    <span><?= htmlspecialchars($exp['name']) ?></span>
                    <span><?= htmlspecialchars($exp['category']) ?></span>
                    <span><?= number_format($exp['amount'], 2, ',', '.') ?> €</span>
                    <span><?= htmlspecialchars($exp['created_at']) ?></span>
                </div>
            <?php endforeach; ?>
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&sort=<?= $sort ?>&order=<?= strtolower($order) ?>"
                       class="<?= $i == $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
