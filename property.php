<?php
require 'config.php';
require 'functions.php';
$id = (int)($_GET['id'] ?? 0);

if (!$id) { 
  header('Location: listings.php'); exit;
 }

$stmt = $pdo->prepare('SELECT a.*, c.nom AS category, t.nom AS transaction_type, u.nom AS owner_name FROM annonces a JOIN categories c ON a.category_id=c.id JOIN types_transaction t ON a.type_transaction_id=t.id JOIN users u ON a.user_id=u.id WHERE a.id = ?');
$stmt->execute([$id]);
$a = $stmt->fetch();

if (!$a) { flash('Annonce introuvable','danger'); 
  header('Location: listings.php'); exit; 
}

$imgs = $pdo->prepare('SELECT filename FROM annonce_images WHERE annonce_id = ? ORDER BY ordre');
$imgs->execute([$id]); $images = $imgs->fetchAll();

include 'templates/header.php';
?>
<h2><?php echo esc($a['titre']); ?></h2>
<p><?php echo nl2br(esc($a['description'])); ?></p>
<p>Prix : <?php echo esc($a['prix']); ?></p>
<p>Ville : <?php echo esc($a['ville']); ?></p>
<p>Type : <?php echo esc($a['category']); ?> — <?php echo esc($a['transaction_type']); ?></p>
<p>Contact : <?php echo esc($a['owner_name']); ?></p>
<?php foreach($images as $im): ?>
  <img src="./assets/uploads/<?php echo $id; ?>/<?php echo esc($im['filename']); ?>" alt="image" style="max-width:300px">
<?php endforeach; ?>

<?php include 'templates/footer.php'; ?>
