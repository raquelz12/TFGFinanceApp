<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT 
        id,
        name,
        COALESCE(amount,0) AS amount,
        COALESCE(completed_amount,0) AS completed_amount
    FROM categories
    WHERE user_id = ?
    ORDER BY name ASC
");
$stmt->execute([$user_id]);
$user_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT id, name
    FROM categories
    WHERE user_id IS NULL
    AND name NOT IN (
        SELECT name
        FROM categories
        WHERE user_id = ?
    )
    ORDER BY name ASC
");
$stmt->execute([$user_id]);
$available_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<main class="categories-page">
    <section class="categories-hero">
        <h1>Gestiona tus categorías de gasto</h1>
        <p>Establece límites y controla tus gastos mensuales</p>
    </section>
    <div class="categories-message">
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= htmlspecialchars($_SESSION['message']) ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </div>
    <section class="categories-actions">
        <button class="button-app" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            + Nueva categoría
        </button>
    </section>
    <section class="categories-section">
        <div class="categories">
            <?php foreach ($user_categories as $cat): 
                $dataAmount = $cat['completed_amount'];
                $dataBudget = $cat['amount'];
            ?>
            <div class="category-progress">
                <div class="card-header">
                    <h4><?= htmlspecialchars($cat['name']); ?></h4>
                    <button class="category-button" title="Editar límite mensual" data-bs-toggle="modal" data-bs-target="#editLimitModal<?= $cat['id'] ?>">
                        <i class="fa-solid fa-pencil"></i>
                    </button>
                    <button class="category-button" title="Eliminar categoría" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal<?= $cat['id'] ?>">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <div class="progress-bar-container">
                    <div 
                        class="progress-bar-fill" 
                        data-amount="<?= $dataAmount ?>" 
                        data-budget="<?= $dataBudget ?>" 
                        style="width: <?= ($dataBudget>0) ? ($dataAmount/$dataBudget*100) : 0 ?>%"
                    >
                    </div>
                </div>
                <p class="progress-info">
                    <?php if ($dataBudget < $dataAmount): ?>
                        <span class="over-budget">Has superado el límite de <?= $dataBudget ?>€</span>
                    <?php else: ?>
                        <?= $dataAmount ?>€ / <?= $dataBudget ?>€
                    <?php endif; ?>
                </p>
            </div>
            <div class="modal fade" id="editLimitModal<?= $cat['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form action="backend/categories/category_action.php" method="POST">
                        <input 
                            type="hidden" 
                            name="action" 
                            value="update"
                        >
                        <input 
                            type="hidden" 
                            name="id" 
                            value="<?= $cat['id'] ?>"
                        >
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Editar límite de <?= htmlspecialchars($cat['name']) ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="form-error" style="display:none;"></div>
                            <div class="modal-body">
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="amount" 
                                    placeholder="Límite mensual en €" 
                                    class="form-control" 
                                    value="<?= $cat['amount'] ?>" 
                                    data-required
                                    data-positive
                                >
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cerrar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="deleteCategoryModal<?= $cat['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form action="backend/categories/category_action.php" method="POST">
                        <input 
                            type="hidden" 
                            name="action" 
                            value="delete"
                        >
                        <input 
                            type="hidden" 
                            name="id" 
                            value="<?= $cat['id'] ?>"
                        >
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Eliminar <?= htmlspecialchars($cat['name']) ?>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="form-error" style="display:none;"></div>
                            <div class="modal-body">
                                ¿Seguro que quieres eliminar esta categoría?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="backend/categories/category_action.php" method="POST">
                        <input 
                            type="hidden" 
                            name="action" 
                            value="insert"
                        >
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Añadir categoría
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="form-error" style="display:none;"></div>
                            <div class="modal-body">
                                <label for="category_id">Categoría:</label>
                                <select name="category_id" class="form-control mt-2" required>
                                    <option value="">Selecciona categoría</option>
                                    <?php foreach ($available_categories as $baseCat): ?>
                                        <option value="<?= $baseCat['id'] ?>"><?= htmlspecialchars($baseCat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="amount" class="mt-2">Límite mensual:</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="amount" 
                                    placeholder="€" 
                                    class="form-control mt-2" 
                                    data-required
                                    data-positive
                                >
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Cerrar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Añadir
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="add-category-button-container">
                <button class="button-app add-category-button" title="Añadir categoría" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
