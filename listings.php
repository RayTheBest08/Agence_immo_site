<?php
require 'config.php';
require 'functions.php';
include 'templates/header.php';

$where = [];
$params = [];
if (!empty($_GET['category'])) {
    $where[] = 'category_id = ?'; $params[] = $_GET['category'];
}
if (!empty($_GET['type_transaction'])) {
    $where[] = 'type_transaction_id = ?'; $params[] = $_GET['type_transaction'];
}
if (!empty($_GET['ville'])) {
    $where[] = 'ville LIKE ?'; $params[] = '%' . $_GET['ville'] . '%';
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS a.* FROM annonces a $whereSql ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$rows = $stmt->fetchAll();
$total = $pdo->query('SELECT FOUND_ROWS()')->fetchColumn();
$pages = ceil($total / $perPage);
?>

<h2>Annonces</h2>
<div class="listings cards">
<?php foreach($rows as $r): ?>
  <article class="card">
    <h3><?php echo esc($r['titre']); ?></h3>
    <p><?php echo esc(substr($r['description'],0,120)); ?>...</p>
    <p>Prix: <?php echo esc($r['prix']); ?> </p>  
    <p> Ville: <?php echo esc($r['ville']); ?><p>
    <p><img src="./assets/uploads/<?php echo $r['id']; ?>/<?php
      $imgStmt = $pdo->prepare('SELECT filename FROM annonce_images WHERE annonce_id = ?');
      $imgStmt->execute([$r['id']]);
      $img = $imgStmt->fetchColumn();
      echo esc($img);
    ?>" alt="Image de l'annonce" style="max-width:300px"></p>
    <a href="#">louer</a>
  </article>
<?php endforeach; ?>
</div>

<nav class="pagination">
  <?php for($i=1;$i<=$pages;$i++): ?>
    <a href="?page=<?php echo $i; ?>" <?php if($i==$page) echo 'class="active"'; ?>><?php echo $i; ?></a>
  <?php endfor; ?>
</nav>

<?php include 'templates/footer.php'; ?>
