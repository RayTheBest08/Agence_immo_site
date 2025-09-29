<?php
require 'config.php';
require 'functions.php';

// vérifier rôle admin
$stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([current_user_id()]);
$role = $stmt->fetchColumn();
if ($role !== 'admin') { flash('Accès admin requis','danger'); header('Location: index.php'); exit; }

// actions admin: supprimer annonce, changer statut
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_annonce'])) {
        $id = (int)$_POST['delete_annonce'];
        $del = $pdo->prepare('DELETE FROM annonces WHERE id = ?');
        $del->execute([$id]);
        flash('Annonce supprimée','success');
        header('Location: admin.php'); exit;
    }
    if (isset($_POST['set_status'])) {
        $id = (int)$_POST['annonce_id'];
        $status = $_POST['status'];
        $u = $pdo->prepare('UPDATE annonces SET statut_disponibilite = ? WHERE id = ?');
        $u->execute([$status, $id]);
        flash('Statut mis à jour','success');
        header('Location: admin.php'); exit;
    }
    if (isset($_POST['delete_user'])) {
        $uid = (int)$_POST['delete_user'];
        $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$uid]);
        flash('Utilisateur supprimé','success');
        header('Location: admin.php'); exit;
    }
}

// pagination simple

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20; $offset = ($page-1)*$perPage;
$an_stmt = $pdo->prepare('SELECT a.*, u.nom AS owner FROM annonces a JOIN users u ON a.user_id=u.id ORDER BY a.created_at DESC LIMIT ? OFFSET ?');
$an_stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$an_stmt->bindValue(2, $offset, PDO::PARAM_INT);
$an_stmt->execute();
$annonces = $an_stmt->fetchAll();

$user_stmt = $pdo->query('SELECT id, nom, email,telephone, role, created_at FROM users ORDER BY created_at DESC');
$users = $user_stmt->fetchAll();

include 'templates/header.php';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" 
integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="table-container">
<h2 class="admin-gestion">Admin - Gestion</h2>

<div class="btn"> 
  <button id="tabAnnonces" class="tab-button active" onclick="showSection('annoncesSection')">Annonces</button> 
  <button id="tabUsers" class="tab-button" onclick="showSection('usersSection')">Utilisateurs</button>
</div>

<section class="admin-annonces-user tab-content active" id="annoncesSection" >
  <div class="add_announce"><h3>Annonces</h3>
    <a href="dashboard.php" class="add-announce-link">creer une annonce</a></div>
 

  <form method="post">
  <table>
    <thead><tr><th>ID</th><th>Titre</th><th>Propriétaire</th><th>Prix</th><th>Ville</th><th>Statut</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($annonces as $a): ?>
      <tr>
        <td><?php echo $a['id']; ?></td>
        <td><?php echo esc($a['titre']); ?></td>
        <td><?php echo esc($a['owner']); ?></td>
        <td><?php echo esc($a['prix']); ?></td>
        <td><?php echo esc($a['ville']); ?></td>
        <td><?php echo esc($a['statut_disponibilite']); ?></td>
        <td>
          <a href="property.php?id=<?php echo $a['id']; ?>"><i class="fa-regular fa-eye"></i></a>
          <button name="delete_annonce" value="<?php echo $a['id']; ?>" type="submit" onclick="return confirm('Supprimer ?');"><i class="fa-solid fa-trash"></i></button>
          <select name="status">
            <option value="disponible">Disponible</option>
            <option value="vendu">Vendu</option>
            <option value="loué">Loué</option>
          </select>
          <input type="hidden" name="annonce_id" value="<?php echo $a['id']; ?>">
          <button name="set_status" type="submit">Mettre à jour</button>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </form>
  <p><a href="admin_export.php">Exporter les annonces (CSV)</a></p>
   
</section>

<section class="admin-annonces-user tab-content active " id="usersSection">
  <div class="add_user">
      <h3>Utilisateurs</h3> 
      <a href="admin_add_user.php" class="add-user-link">Ajouter un utilisateur</a>

  </div>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Email</th>
        <th>Téléphone</th>
        <th>Rôle</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($users as $u): ?>
      <tr>
        <td><?php echo $u['id']; ?></td>
        <td><?php echo esc($u['nom']); ?></td>
        <td><?php echo esc($u['email']); ?></td>
        <td><?php echo esc($u['telephone']); ?></td>
        <td><?php echo esc($u['role']); ?></td>
        <td>
          <?php if($u['id'] != current_user_id()): ?>
            <form method="post" style="display:inline">
              <button name="delete_user" value="<?php echo $u['id']; ?>" type="submit" onclick="return confirm('Supprimer utilisateur ?');">Supprimer</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</section>
</div>
<?php include 'templates/footer.php'; ?>

<script src="./assets/js/main.js">
</script>
