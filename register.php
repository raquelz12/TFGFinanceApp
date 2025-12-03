<?php include 'includes/header.php'; ?>
<main>
    <div class="container">
        <div class="register-hero">
            <h1>Registrarse</h1>
        </div>
        <form class="register-form" action="authenticate.php" method="POST">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button class="button-app" type="submit">Registrarse</button>
        </form>
        <div class="register-link">
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>