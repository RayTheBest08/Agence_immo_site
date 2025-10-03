<?php
require_once __DIR__ . '/../config.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agence Immobilière</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- les fichiers de styles -->
  <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
      <link rel="stylesheet" href="./assets/css/register.css">
      <link rel="stylesheet" href="./assets/css/admin.css">


</head>
 
<body>

<div id="js-preloader" class="js-preloader ">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  
<header class="header-main">
    <div class="logo-container">
        <img src="logo" alt="Logo Agence Immobilière" class="logo-img">
        <a href="./index.php" class="logo-link">
            <h1 class="site-title">Agence Immobilière</h1>
        </a>
    </div>

    <nav class="main-nav">
        <a href="./index.php" class="nav-item"> <i class="fas fa-home"></i> Accueil</a>
        <a href="./about.php" class="nav-item"> <i class="fas fa-info-circle"></i> À propos</a>
        <a href="./services.php" class="nav-item"> <i class="fas fa-concierge-bell"></i> Services</a>
        <a href="./listings.php" class="nav-item"> <i class="fas fa-th-list"></i> Annonces</a>

        <div class="nav-auth">
            <?php if(is_logged_in()): ?>
                <a href="./dashboard.php" class="nav-item nav-item-dashboard"> <i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="./profile.php" class="nav-item" onclick="openModal(event)">Profil</a>
                <a href="./logout.php" class="nav-item nav-item-logout">Déconnexion</a>
            <?php else: ?>
                <a href="./register.php" class="nav-item">Inscription</a>
                <a href="./login.php" class="nav-item nav-item-login">Connexion</a>
            <?php endif; ?>
        </div>
    </nav>
</header>


<main class="container">

<?php require_once __DIR__ . '/../functions.php'; show_flash(); ?>
