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
        COALESCE(completed_amount,0) AS completed_amount,
        COALESCE(amount,0) AS amount,
        objective_date
    FROM objectives
    WHERE user_id = ?
");
$stmt->execute([$user_id]);
$user_objectives = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php'; ?>

<main class="objectives-page">
    <section class="objectives-hero">
        <h1>Objetivos de ahorro</h1>
        <p>Cada paso cuenta para alcanzar tus metas</p>
    </section>
    <div class="objectives-message">
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?= htmlspecialchars($_SESSION['message']) ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </div>
    <section class="objectives-actions">
        <button class="button-app" data-bs-toggle="modal" data-bs-target="#addObjectiveModal">
            + Nuevo objetivo
        </button>
    </section>
    <section class="objectives-list">
        <div class="objectives">
            <?php foreach ($user_objectives as $obj): 
                $dataAmount = $obj['amount'];
                $dataTarget = $obj['completed_amount'];
            ?>
            <div class="objective-card">
                <div class="card-header">
                    <h2><?= htmlspecialchars($obj['name']) ?></h2>
                    <div class="objective-buttons">
                        <button class="objective-button" title="Editar" data-bs-toggle="modal" data-bs-target="#editModal<?= $obj['id'] ?>">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button class="objective-button" title="Añadir dinero" data-bs-toggle="modal" data-bs-target="#addMoneyModal<?= $obj['id'] ?>">
                            <i class="fa-solid fa-piggy-bank"></i>
                        </button>
                        <button class="objective-button" title="Eliminar objetivo" data-bs-toggle="modal" data-bs-target="#deleteObjectiveModal<?= $obj['id'] ?>">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php if ($dataTarget < $dataAmount): ?>
                    <p class="objective-amount"><?= $dataTarget ?>€ / <?= $dataAmount ?>€</p>
                <?php else: ?>
                    <p class="objective-completed">Has conseguido tu objetivo!</p>
                <?php endif; ?>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= ($dataAmount>0) ? ($dataTarget/$dataAmount*100) : 0 ?>%"></div>
                    </div>
                <p class="objective-date">Fecha objetivo: <?= htmlspecialchars($obj['objective_date']) ?></p>
            </div>
            <div class="modal fade" id="editModal<?= $obj['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form action="backend/objectives/objective_action.php" method="POST">
                        <input 
                            type="hidden" 
                            name="action" 
                            value="update"
                        >
                        <input 
                            type="hidden" 
                            name="id" 
                            value="<?= $obj['id'] ?>"
                        >
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar objetivo de <?= htmlspecialchars($obj['name']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="form-error" style="display:none;"></div>
                            <div class="modal-body">
                                <input 
                                    type="number" 
                                    step="0.01"
                                    name="amount" 
                                    value="<?= $dataAmount ?>" 
                                    class="form-control" 
                                    data-required
                                    data-positive
                                >
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="deleteObjectiveModal<?= $obj['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form action="backend/objectives/objective_action.php" method="POST">
                        <input 
                            type="hidden" 
                            name="action" 
                            value="delete"
                        >
                        <input 
                            type="hidden" 
                            name="id" 
                            value="<?= $obj['id'] ?>"
                        >
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Eliminar <?= htmlspecialchars($obj['name']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="form-error" style="display:none;"></div>
                            <div class="modal-body">
                                ¿Seguro que quieres eliminar este objetivo?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="addMoneyModal<?= $obj['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form action="backend/objectives/objective_action.php" method="POST">
                        <input 
                            type="hidden" 
                            name="action" 
                            value="add_money"
                        >
                        <input 
                            type="hidden" 
                            name="id" 
                            value="<?= $obj['id'] ?>"
                        >
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Añadir dinero a <?= htmlspecialchars($obj['name']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="form-error" style="display:none;"></div>
                            <div class="modal-body">
                                <label for="amount">Cantidad a añadir:</label>
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Añadir</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="modal fade" id="addObjectiveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="backend/objectives/objective_action.php" method="POST">
                        <input type="hidden" name="action" value="insert">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Añadir objetivo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="form-error" style="display:none;"></div>
                            <div class="modal-body">
                                <label for="name">Nombre del objetivo:</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    placeholder="Viaje a Japón (max 30 caracteres)" 
                                    class="form-control mt-2" 
                                    data-required
                                    data-minlength="3"
                                    data-maxlength="30"
                                >
                                <label for="targetAmount" class="mt-2">Cantidad objetivo:</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="targetAmount" 
                                    id="targetAmount" 
                                    placeholder="€" 
                                    class="form-control mt-2" 
                                    data-required
                                    data-positive
                                >
                                <label for="amount" class="mt-2">Dinero inicial:</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    name="amount" 
                                    id="amount" 
                                    placeholder="€" 
                                    class="form-control mt-2" 
                                    data-required
                                    data-positive
                                >
                                <label for="objective_date" class="mt-2">Fecha objetivo:</label>
                                <input 
                                    type="date" 
                                    name="objective_date" 
                                    id="objective_date" 
                                    class="form-control mt-2" 
                                    data-required
                                    data-future
                                >
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Añadir</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="add-objective-button-container">
                <button class="button-app add-objective-button" title="Añadir objetivo" data-bs-toggle="modal" data-bs-target="#addObjectiveModal">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>