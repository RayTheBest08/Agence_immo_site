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
?>
<div id="profileModal" class="modal-overlay">
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
<?php include 'templates/footer.php'; ?>

<style>/* ------------------------------------ */
/* MODALE OVERLAY             */
/* ------------------------------------ */

.modal-overlay {
   
}

/* ------------------------------------ */
/* CONTENU DU MODALE          */
/* ------------------------------------ */

.modal-content {
    background: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    /* Limite la largeur du modale */
    max-width: 450px;
    width: 90%;
    position: relative; /* Nécessaire pour positionner le bouton de fermeture */
    transform: translateY(-50px); /* Léger décalage pour l'animation */
    opacity: 0;
    transition: all 0.3s ease-out;
}

/* État Actif (affiché par JS) */
.modal-overlay.is-open .modal-content {
    transform: translateY(0);
    opacity: 1;
}
.modal-overlay.is-open {
    display: flex; /* Remplace 'none' par 'flex' lorsque visible */
}

/* ------------------------------------ */
/* Éléments du Formulaire        */
/* ------------------------------------ */

.modal-content h2 {
    color: #333;
    margin-top: 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.profile-form label {
    display: block;
    margin-top: 10px;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

.profile-form input[type="text"],
.profile-form input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* S'assure que padding n'augmente pas la largeur totale */
}

.profile-form hr {
    border: none;
    border-top: 1px solid #eee;
    margin: 20px 0;
}

.profile-form h3 {
    font-size: 1.1em;
    color: #007bff;
    margin-top: 0;
    margin-bottom: 10px;
}

.btn-submit-profile {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 1em;
    cursor: pointer;
    margin-top: 20px;
    transition: background-color 0.2s;
}

.btn-submit-profile:hover {
    background-color: #0056b3;
}

/* Bouton de fermeture (X) */
.modal-close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #aaa;
    line-height: 1;
}

.modal-close-btn:hover {
    color: #333;
}</style>

<script>
    // Ouvre le modale
function openModal(event) {
    if (event) {
        event.preventDefault(); // Empêche le lien de sauter en haut de page
    }
    const modal = document.getElementById('profileModal');
    // Ajoute la classe CSS pour afficher l'overlay et le contenu
    modal.classList.add('is-open'); 
}

// Ferme le modale
function closeModal() {
    const modal = document.getElementById('profileModal');
    // Retire la classe CSS pour masquer l'overlay et le contenu
    modal.classList.remove('is-open');
}

// Optionnel: Fermer le modale si l'utilisateur clique en dehors
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('profileModal');
    
    // Écouter les clics sur l'overlay (pas sur le contenu)
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Fermer avec la touche ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === "Escape" && modal.classList.contains('is-open')) {
            closeModal();
        }
    });
});
</script>