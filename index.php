<?php

require 'config.php';
require 'functions.php';
include 'templates/header.php';

// Les annonces  recents 
// $stmt = $pdo->query("SELECT a.*, c.nom AS categories FROM annonces a JOIN categories c ON a.category_id=c.id ORDER BY a.created_at DESC LIMIT 6");
$stmt = $pdo->query("SELECT a.*, c.nom AS category FROM annonces a JOIN categories c ON a.category_id=c.category_id ORDER BY a.created_at DESC LIMIT 6");
$annonces = $stmt->fetchAll();
?>

<p>Recherche rapide:</p>
<form action="listings.php" method="post" class="search-form">
  <select name="category">
    <option value="">--Tous--</option>
    <option value="1">Propriété</option>
    <option value="2">Voiture</option>
    <option value="3">Terrain</option>
  </select>
  <select name="type_transaction">
    <option value="">--Location/Vente--</option>
    <option value="1">Vente</option>
    <option value="2">Location</option>
  </select>
  <input type="text" name="ville" placeholder="Ville">
  <button type="submit">Rechercher</button>
</form>

<section class="cards">
<?php foreach($annonces as $a): ?>
  <article class="card">
        <?php
      $imgStmt = $pdo->prepare('SELECT filename FROM annonce_images WHERE annonce_id = ? ORDER BY ordre LIMIT 1');
      $imgStmt->execute([$a['id']]);
      $img = $imgStmt->fetchColumn();
      if ($img) {
        $imgSrc = "./assets/uploads/" . esc($a['id']) . "/" . esc($img);
      } else {
        $imgSrc = "./assets/img/no-image.jpg"; // image par défaut à prévoir
      }
    ?>
    <img src="<?php echo $imgSrc; ?>" alt="Image de l'annonce" style="max-width:300px">
    <h3><?php echo esc($a['titre'] ?? 'Titre'); ?></h3>
    <p><?php echo esc(substr($a['description'] ?? '',0,120)); ?>...</p>
    <a href="<?php echo $a['id']; ?>">louer</a>
  </article>
<?php endforeach; ?>
</section>

<?php include 'templates/footer.php'; ?>

