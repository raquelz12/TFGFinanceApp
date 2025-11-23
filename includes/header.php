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
</head>
<header>
    <nav class="navbar navbar-expand-lg sticky-top finance-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fa-solid fa-money-bills"></i>
                Finance App
            </a>
            <div class="navbar-profile">
                <a href="profile.php" class="profile-btn">
                    <i class="fa-solid fa-user"></i>
                </a>
            </div>
            <div class="navbar-add-transaction">
                <a href="add_transaction.php" class="add-transaction-btn">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>
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
                        <a class="nav-link" href="transactions.php">Transacciones</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
