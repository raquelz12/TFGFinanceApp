<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$user_id = $_SESSION['user_id'] ?? null;

$stmt = $pdo->prepare("
    SELECT id, name
    FROM categories
    WHERE user_id = ?
    ORDER BY name ASC
");
$stmt->execute([$user_id]);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="modal fade" id="ModalAddExpense" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="backend/expenses/expense_action.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Añadir gasto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="form-error" style="display:none;"></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="name">
                            Descripción:
                        </label>
                        <input 
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            placeholder="Cena con amigos"
                            data-required
                            data-minlength="3"
                            data-maxlength="100"
                        >
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="amount">
                            Cantidad:
                        </label>
                        <input 
                            type="number"
                            name="amount"
                            id="amount"
                            class="form-control"
                            step="0.01"
                            min="0"
                            placeholder="€"
                            data-required
                            data-positive
                        >
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="category_id">
                            Categoría:
                        </label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Selecciona categoría</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <input type="hidden" name="action" value="add">
                    <button id="saveExpenseButton" type="submit" class="btn btn-primary">
                        Guardar gasto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
