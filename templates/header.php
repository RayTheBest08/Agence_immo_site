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
<header class="site-header">

<div class="logo">
  <img src="logo" alt="">
    <a href="./index.php"><h1>Agence Immobilière</h1></a>
</div>

<nav class="nav">
    <a href="./index.php"> <i class="fas fa-home"></i> Accueil</a>
    <a href="./about.php"> <i class="fas fa-info-circle"></i> À propos</a>
    <a href="./services.php"> <i class="fas fa-concierge-bell"></i> Services</a>
    <a href="./listings.php"> <i class="fas fa-th-list"></i> Annonces</a>
    <?php if(is_logged_in()): ?>
      <a href="./dashboard.php"> <i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="./profile.php" onclick="openModal(event)">Profil</a>
      <a href="./logout.php">Déconnexion</a>
    <?php else: ?>
      <a href="./register.php">Inscription</a>
      <a href="./login.php">Connexion</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">

<?php require_once __DIR__ . '/../functions.php'; show_flash(); ?>
