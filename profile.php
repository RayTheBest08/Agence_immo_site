<?php
require 'config.php';
require 'functions.php';
if (!is_logged_in()) { flash('Connectez-vous pour voir votre profil','danger'); header('Location: login.php'); exit; }
$user_id = current_user_id();
$stmt = $pdo->prepare('SELECT id, nom, email, telephone, role, created_at FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
include 'templates/header.php';
?>
<div id="profileModal" class="modal-overlay">
  <section class="modal-content">
    
    <h2>Modifier mon profil</h2>
    <button class="modal-close-btn" onclick="closeModal()">X</button>
    <p><a href="edit_profile.php" onclick="openModal(event)">Modifier mon profil</a></p>
<p><a href="#" onclick="openModal(event)">Modifier mon profil</a></p>
    
    <form method="post" class="profile-form">
      <label for="nom">Nom</label>
      <input id="nom" name="nom" value="<?php echo esc($user['nom']); ?>" required>
      
      <button type="submit" class="btn-submit-profile">Enregistrer</button>
    </form>
    
  </section>
</div>
<?php include 'templates/footer.php'; ?>

<style>
/* Cache la fenêtre par défaut, couvre l'écran, et la positionne à droite */
.modal-overlay {
    display: none; 
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6); 
    z-index: 1000; 
    /* Positionne le contenu à droite */
    display: flex;
    justify-content: flex-end;
}

/* Le Panneau qui glisse */
.modal-content {
    background: #ffffff;
    height: 100%; /* Pleine hauteur */
    width: 400px; /* Largeur du panneau */
    max-width: 90%; 
    padding: 30px;
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2);
    position: relative; 
    
    /* Déplace le panneau complètement à droite (hors écran) */
    transform: translateX(100%); 
    transition: transform 0.4s ease-out; /* Ajoute l'animation */
}

/* État Actif (Affiché) */
.modal-overlay.is-open {
    display: flex; /* Révèle l'arrière-plan */
}

.modal-overlay.is-open .modal-content {
    /* Ramène le panneau à sa position d'origine (glisse vers la gauche) */
    transform: translateX(0); 
}

/* Style du bouton de fermeture (X) */
.modal-close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #aaa;
}

/* Styles du formulaire (ajoutez le reste de vos styles d'input/label ici) */
.profile-form label { display: block; margin-top: 10px; font-weight: bold; }
.profile-form input { width: 100%; padding: 10px; margin-bottom: 15px; box-sizing: border-box; }
/* ... */
    


</style>

<script>
    function openModal(event) {
    // 1. Annule l'action par défaut du lien. C'est l'étape CRUCIALE.
    if (event) {
        event.preventDefault(); 
    }

    // 2. Trouve et affiche le modale
    const modal = document.getElementById('profileModal');
    if (modal) {
        modal.classList.add('is-open'); 
    }
}
// Ouvre le modale et ajoute la classe 'is-open'
function openModal(event) {
    if (event) {
        event.preventDefault(); // Empêche la navigation vers 'edit_profile.php'
    }
    const modal = document.getElementById('profileModal');
    modal.classList.add('is-open'); 
}

// Ferme le modale et retire la classe 'is-open'
function closeModal() {
    const modal = document.getElementById('profileModal');
    modal.classList.remove('is-open');
}

// Écouteurs d'événements pour l'amélioration UX
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('profileModal');
    
    // Fermer en cliquant sur l'overlay (le fond noir)
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