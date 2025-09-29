<?php
require 'config.php';
require 'functions.php';
if (!is_logged_in()) { flash('Connectez-vous pour accéder au dashboard', 'danger'); header('Location: login.php'); exit; }
$user_id = current_user_id();

// create annonce
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_annonce'])) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $ville = $_POST['ville'];
    $category_id = $_POST['category_id'];
    $type_transaction_id = $_POST['type_transaction_id'];

    $stmt = $pdo->prepare('INSERT INTO annonces (user_id,category_id,type_transaction_id,titre,description,prix,ville,created_at) VALUES (?,?,?,?,?,?,?,NOW())');
    $stmt->execute([$user_id,$category_id,$type_transaction_id,$titre,$description,$prix,$ville]);
    $annonce_id = $pdo->lastInsertId();

    // handle image upload (single image minimal)
    
    if (!empty($_FILES['image']['tmp_name'])) {
        $uploadDir = __DIR__ . '/assets/uploads/' . $annonce_id;
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $tmp = $_FILES['image']['tmp_name'];
        $name = basename($_FILES['image']['name']);
        $target = $uploadDir . '/' . $name;
        move_uploaded_file($tmp, $target);
        $stmt = $pdo->prepare('INSERT INTO annonce_images (annonce_id,filename,ordre) VALUES (?,?,1)');
        $stmt->execute([$annonce_id,$name]);
    }
    flash('Annonce créée', 'success');
    header('Location: dashboard.php'); exit;
}

// list user's annonces
$stmt = $pdo->prepare('SELECT * FROM annonces WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$my_annonces = $stmt->fetchAll();
include 'templates/header.php';
?>
<h2>Mon Dashboard</h2>
<section>
  <h3>Créer une annonce</h3>
  <form method="post" enctype="multipart/form-data">
    <input name="titre" required placeholder="Titre">
    <textarea name="description" required placeholder="Description"></textarea>
    <label>
    <input name="prix" required placeholder="Prix">
    <input name="ville" placeholder="Ville">
    <select name="category_id"><option value="1">Propriété</option>
    <option value="2">Voiture</option>
    <option value="3">Terrain</option>
  </select>
    <select name="type_transaction_id">
    <option value="1">Vente</option>
    <option value="2">Location</option>
  </select>
    <input type="file" name="image"  >
    <button name="create_annonce" type="submit">Publier</button>
  </form>
</section>

<section>
  <h3>Mes annonces</h3>
  <?php foreach($my_annonces as $a): ?>
    <div class="annonce-item">
      <h4><?php echo esc($a['titre']); ?></h4>
      <p><?php echo esc($a['description']); ?></p>
      <a href="/property.php?id=<?php echo $a['id']; ?>">Voir</a>
    </div>
  <?php endforeach; ?>
</section>

<?php include 'templates/footer.php'; ?>
