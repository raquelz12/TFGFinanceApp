<?php
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

include 'includes/header.php'; ?>

<main>
    <div class="container login-container">
        <div class="login-hero">
            <h1>Iniciar Sesión</h1>
        </div>
        <div class="login-message">
            <?php
            if (isset($_SESSION['message'])) {
                echo '<p class="message">' . htmlspecialchars($_SESSION['message']) . '</p>';
                unset($_SESSION['message']);
            }
            ?>
        </div>
        <form class="login-form" action="backend/authentication/login_action.php" method="POST">
            <div class="form-group">
                <label for="email">
                    Correo Electrónico:
                </label>
                <input 
                    type="text" 
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
                    placeholder="**********" 
                    data-required
                    data-minlength="8"
                    data-maxlength="20"
                >
            </div>
            <button class="button-app" type="submit">
                Iniciar Sesión
            </button>
        </form>
        <div class="register-link">
            <p>¿No tienes una cuenta? 
                <a href="register.php">Regístrate aquí</a>
            </p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>