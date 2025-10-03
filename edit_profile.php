<?php
require 'config.php';
require 'functions.php';
if (!is_logged_in()) { flash('Connectez-vous pour modifier votre profil','danger'); header('Location: login.php'); exit; }
$user_id = current_user_id();

// charger les infos actuelles
$stmt = $pdo->prepare('SELECT id, nom, email, telephone FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user) { flash('Utilisateur introuvable','danger'); header('Location: logout.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $telephone = trim($_POST['telephone']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // validation minimale
    if (!$nom) {
        flash('Le nom est requis','danger');
    } else {
        // si l'utilisateur veut changer le mot de passe
        if ($new_password) {
            if ($new_password !== $confirm_password) {
                flash('Le nouveau mot de passe et la confirmation ne correspondent pas','danger');
            } else {
                // vérifier mot de passe actuel
                $s = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
                $s->execute([$user_id]);
                $row = $s->fetch();
                if (!$row || !password_verify($current_password, $row['password_hash'])) {
                    flash('Mot de passe actuel invalide','danger');
                } else {
                    $hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $upd = $pdo->prepare('UPDATE users SET nom = ?, telephone = ?, password_hash = ?, updated_at = NOW() WHERE id = ?');
                    $upd->execute([$nom, $telephone, $hash, $user_id]);
                    flash('Profil et mot de passe mis à jour','success');
                    header('Location: profile.php'); exit;
                }
            }
        } else {
            // mise à jour sans changement de mot de passe
            $upd = $pdo->prepare('UPDATE users SET nom = ?, telephone = ?, updated_at = NOW() WHERE id = ?');
            $upd->execute([$nom, $telephone, $user_id]);
            flash('Profil mis à jour','success');
            header('Location: profile.php'); exit;
        }
    }
}
include 'templates/header.php';
?><div id="profileModal" class="modal-overlay">
  <section class="modal-content">
    
    <h2>Modifier mon profil</h2>
    <button class="modal-close-btn" onclick="closeModal()">X</button>
    
    <form method="post" class="profile-form">
      <label for="nom">Nom</label>
      <input id="nom" name="nom" value="<?php echo esc($user['nom']); ?>" required>
      
      <label for="telephone">Téléphone</label>
      <input id="telephone" name="telephone" value="<?php echo esc($user['telephone']); ?>">

      <hr>
      
      <h3>Changer le mot de passe (optionnel)</h3>
      <label for="current_password">Mot de passe actuel</label>
      <input id="current_password" name="current_password" type="password">
      
      <label for="new_password">Nouveau mot de passe</label>
      <input id="new_password" name="new_password" type="password">
      
      <label for="confirm_password">Confirmer le nouveau mot de passe</label>
      <input id="confirm_password" name="confirm_password" type="password">

      <button type="submit" class="btn-submit-profile">Enregistrer</button>
    </form>
    
  </section>
</div>
<!-- <button onclick="openModal()">Modifier mon Profil</button> -->

<?php include 'templates/footer.php'; ?>

<style>

.modal-overlay {
    display: none; 
    position: fixed; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    margin-top:3rem;
    background-color: rgba(0, 0, 0, 0.7); 
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000; 
}

.modal-overlay.active {
    display: flex; 
}


.modal-content {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    width: 90%;
    position: relative; 
    
    transform: translateY(-50px);
    transition: transform 0.3s ease-out;
}


.modal-overlay.active .modal-content {
    transform: translateY(0);
}



.modal-content h2 {
    color: #333;
    margin-top: 0;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.modal-close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #888;
}

.profile-form label,
.profile-form input {
    display: block;
    width: 100%;
    margin-bottom: 10px;
}

.profile-form input[type="text"],
.profile-form input[type="password"] {
    padding: 10px;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.profile-form hr {
    margin: 20px 0;
    border: 0;
    border-top: 1px solid #eee;
}

.btn-submit-profile {
    display: block;
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.btn-submit-profile:hover {
    background-color: #0056b3;
}
</style>
<script>
Document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('profileModal');
    
    if (!modal) return;

    window.closeModal = function() {
        modal.classList.remove('active');
        document.body.style.overflow = ''; 
    };

    window.openModal = function(event) {
        modal.classList.add('active'); 
        document.body.style.overflow = 'hidden'; 
    };

    modal.addEventListener('onclick', function(event) {
        if (event.target === modal) {
            window.closeModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.classList.contains('active')) {
            window.closeModal();
        }
    });
});
</script>