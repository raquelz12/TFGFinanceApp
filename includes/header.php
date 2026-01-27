<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance App</title>
    <link rel="stylesheet" href="public/css/main.css">
    <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">
    <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous">
    </script>
    <script 
    src="https://kit.fontawesome.com/c7ca284d32.js" 
    crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="public/js/app.js"></script>
</head>
<?php include 'modals/add_expense_modal.php'; ?>
<body>
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg finance-navbar">
            <div class="container-fluid">
                <a class="navbar-brand" href="app_dashboard.php">
                    <i class="fa-solid fa-money-bills"></i>
                    Finance App
                </a>
                <div class="navbar-profile">
                    <?php if (isset($_SESSION['user_name'])): ?>
                    <a href="profile.php" class="profile-btn" title="Mi Perfil">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <?php else: ?>
                    <a href="login.php" class="profile-btn" title="Iniciar Sesión">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="navbar-add-transaction">
                    <a class="add-transaction-btn" title="Añadir Gasto" data-bs-toggle="modal" data-bs-target="#ModalAddExpense">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </div>
                <?php endif; ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="app_dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="categories.php">Categorías</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="objectives.php">Objetivos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="expenses.php">Gastos</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
