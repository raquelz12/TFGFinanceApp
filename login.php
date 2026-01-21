<?php include 'includes/header.php'; ?>

<main>
    <div class="container login-container">
        <div class="login-hero">
            <h1>Iniciar Sesión</h1>
        </div>
        <form class="login-form" action="authenticate.php" method="POST">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button class="button-app" type="submit">Iniciar Sesión</button>
        </form>
        <div class="register-link">
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>