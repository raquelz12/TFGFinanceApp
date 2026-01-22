<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'backend/config/connection.php';

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
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input 
                            type="text"
                            name="name"
                            class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad (€)</label>
                        <input 
                            type="number"
                            name="amount"
                            class="form-control"
                            step="0.01"
                            min="0"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="category_id" class="form-select" required>
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
                    <button type="submit" name="action" value="insert" class="btn btn-primary">
                        Guardar gasto
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
