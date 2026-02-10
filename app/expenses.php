<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$limit = 10;
$page = max(1, (int)($_GET['page'] ?? 1));

$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM expenses
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$totalExpenses = (int)$stmt->fetchColumn();

$totalPages = max(1, ceil($totalExpenses / $limit));
$page = min($page, $totalPages);
$offset = ($page - 1) * $limit;

$allowedSorts = [
    'name'     => 'e.name',
    'category' => 'c.name',
    'amount'   => 'e.amount',
    'date'     => 'e.created_at'
];

$sort = $_GET['sort'] ?? 'date';
$sort = array_key_exists($sort, $allowedSorts) ? $sort : 'date';

$order = strtolower($_GET['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
$orderSql = $order === 'asc' ? 'ASC' : 'DESC';

$stmt = $pdo->prepare("
    SELECT 
        e.id,
        e.name,
        e.amount,
        e.created_at,
        c.name AS category
    FROM expenses e
    LEFT JOIN categories c ON e.category_id = c.id
    WHERE e.user_id = :user_id
    ORDER BY {$allowedSorts[$sort]} $orderSql
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

function sortArrow(string $column, string $currentSort, string $currentOrder): string {
    if ($column !== $currentSort) return '';
    return $currentOrder === 'asc' ? ' ↑' : ' ↓';
}

function sortLink(string $label, string $column, string $currentSort, string $currentOrder, int $page): string {
    $newOrder = ($currentSort === $column && $currentOrder === 'asc') ? 'desc' : 'asc';
    return "<a href='?sort=$column&order=$newOrder&page=$page'>$label</a>";
}

include 'includes/header.php';
?>

<main class="expenses-page">
    <section class="expenses-hero">
        <h2>Gastos</h2>
        <p>Visualiza todos tus gastos</p>
    </section>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="form-error">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
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
                <span>Acciones</span>
            </div>
            <?php foreach ($expenses as $exp): ?>
            <div class="transaction-item">
                <span><?= htmlspecialchars($exp['name']) ?></span>
                <span><?= htmlspecialchars($exp['category']) ?></span>
                <span><?= number_format($exp['amount'], 2, ',', '.') ?> €</span>
                <span><?= htmlspecialchars(date('d/m/Y', strtotime($exp['created_at']))) ?></span>
                <span>
                    <button
                        class="expense-button"
                        data-bs-toggle="modal"
                        data-bs-target="#editExpenseModal<?= $exp['id'] ?>">
                        <i class="fas fa-pencil"></i>
                    </button>
                    <button
                        class="expense-button"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteExpenseModal<?= $exp['id'] ?>">
                        <i class="fas fa-trash"></i>
                    </button>
                </span>
            </div>
            <div class="modal fade" id="editExpenseModal<?= $exp['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="backend/expenses/expense_action.php" method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar gasto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="form-error" style="display:none;"></div>
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="form-error">
                                <?= htmlspecialchars($_SESSION['message']) ?>
                            </div>
                            <?php unset($_SESSION['message']); ?>
                        <?php endif; ?>
                        <div class="modal-body">
                            <input 
                                type="hidden" 
                                name="action" 
                                value="edit"
                                >
                            <input 
                                type="hidden" 
                                name="expense_id" 
                                value="<?= $exp['id'] ?>"
                                >
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control"
                                    value="<?= htmlspecialchars($exp['name']) ?>"
                                    data-required
                                    data-minlength="3"
                                    data-maxlength="100"
                                    >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cantidad</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="amount"
                                    id="amount"
                                    class="form-control"
                                    value="<?= $exp['amount'] ?>"
                                    data-required
                                    data-positive
                                    >
                            </div>
                            <small class="text-muted">
                                Categoría: <?= htmlspecialchars($exp['category']) ?> (no editable)
                            </small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button id="saveChangesButton" type="submit" class="btn btn-primary">
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="deleteExpenseModal<?= $exp['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form action="backend/expenses/expense_action.php" method="POST">
                        <input 
                            type="hidden" 
                            name="action" 
                            value="delete"
                        >
                        <input 
                            type="hidden" 
                            name="expense_id" 
                            value="<?= $exp['id'] ?>"
                        >
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Eliminar <?= htmlspecialchars($exp['name']) ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="form-error" style="display:none;"></div>
                            <div class="modal-body">
                                ¿Seguro que quieres eliminar este gasto?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <button id="deleteExpenseButton" type="submit" class="btn btn-danger">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if (empty($expenses)): ?>
                <p class="no-transactions m-2">No hay gastos para mostrar.</p>
            <?php endif;
            if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&sort=<?= $sort ?>&order=<?= $order ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
