<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT name, email FROM users WHERE id = ?"
);
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch();

include 'includes/header.php'; ?>

<main class="profile-page">
    <div class="container">
        <h1>Mi perfil</h1>
        <section class="profile-card">
            <h2>Información personal</h2>
            <form action="backend/profile/update_profile.php" method="POST">
                <div class="form-error text-danger" style="display:none;"></div>
                <?php
                if (isset($_SESSION["message"])) {
                    echo '<div class="profile-message">';
                    foreach ($_SESSION["message"] as $error) {
                        echo '<p>' . htmlspecialchars($error) . '</p>';
                    }
                    echo '</div>';
                    unset($_SESSION["message"]);
                }
                ?>
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="<?= htmlspecialchars($user['name']) ?>"
                        data-required
                        data-minlength="3"
                        data-maxlength="50"
                    >
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="<?= htmlspecialchars($user['email']) ?>"
                        disabled
                    >
                </div>
                <button class="btn btn-primary">Guardar cambios</button>
            </form>
        </section>
        <section class="profile-card">
            <h2>Seguridad</h2>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#ModalChangePassword">
                Cambiar contraseña
            </button>
            <div class="modal fade" id="ModalChangePassword" tabindex="-1" aria-labelledby="ModalChangePassword" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="ModalChangePassword">Cambiar contraseña</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="backend/profile/update_password.php" method="POST">
                            <div class="form-error text-danger mb-2" style="display:none;"></div>
                            <div class="form-group">
                                <label for="current_password">
                                    Contraseña actual
                                </label>
                                <input 
                                    type="password" 
                                    name="current_password" 
                                    id="current_password" 
                                    data-required
                                    data-minlength="8"
                                    data-maxlength="50"
                                >
                            </div>
                            <div class="form-group">
                                <label for="new_password">
                                    Nueva contraseña
                                </label>
                                <input 
                                    type="password" 
                                    name="new_password" 
                                    id="new_password" 
                                    data-required
                                    data-minlength="8"
                                    data-maxlength="50"
                                >
                            </div>
                            <div class="form-group">
                                <label for="confirm_new_password">
                                    Confirmar nueva contraseña
                                </label>
                                <input 
                                    type="password" 
                                    name="confirm_new_password" 
                                    id="confirm_new_password" 
                                    data-required
                                    data-minlength="8"
                                    data-maxlength="50"
                                >
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                        class="button-app"
                                        data-bs-dismiss="modal">
                                    Cerrar
                                </button>
                                <button type="submit" class="button-app">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            <button class="btn btn-outline-danger" onclick="window.location.href='backend/authentication/logout.php'">
                Cerrar sesión
            </button>
        </section>
    </div>
</main>

<?php include 'includes/footer.php'; ?>