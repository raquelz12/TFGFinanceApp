<?php 
session_start();

$errors = $_SESSION["register_errors"] ?? [];
$old    = $_SESSION["old_data"] ?? [];
unset($_SESSION["register_errors"], $_SESSION["old_data"]);
require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

include 'includes/header.php'; ?>

<main>
    <div class="container register-container">
        <div class="register-hero">
            <h1>Registrarse</h1>
        </div>
        <?php if ($errors): ?>
        <div class="register-message">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
        <?php endif ?>
        <form class="register-form" action="backend/authentication/register_action.php" method="POST">
            <div class="form-error text-danger mb-2" style="display:none;"></div>
            <div class="form-group">
                <label for="name">
                    Nombre:
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Jhon Doe" 
                    data-required 
                    data-minlength="3" 
                    data-maxlength="50" 
                >
            </div>
            <div class="form-group">
                <label for="email">
                    Correo Electrónico:
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="usuario@email.com" 
                    data-required
                    data-minlength="10"
                    data-maxlength="50"
                >
            </div>
            <div class="form-group">
                <label for="password">
                    Contraseña:
                </label>
                <input 
                    type="password"
                    id="password" 
                    name="password"
                    placeholder="********" 
                    data-required
                    data-minlength="8"
                    data-maxlength="50"
                >
            </div>
            <div class="form-group">
                <label for="confirm_password">
                    Confirmar Contraseña:
                </label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="********" 
                    data-required
                    data-minlength="8"
                    data-maxlength="50"
                >
            </div>
            <button class="button-app" type="submit">
                Registrarse
            </button>
        </form>
        <div class="register-link">
            <p>¿Ya tienes cuenta? 
                <a href="login.php">
                    Inicia sesión aquí
                </a>
            </p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>