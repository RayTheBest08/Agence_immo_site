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


<main id="profileSidebar" class="profile-container">
    
    <button id="closeSidebarBtn" class="close-btn">&#10006;</button> 

    <h1>Mon Profil</h1>
    
    <div class="profile-info">
        <p>
            <strong class="info-label">Nom :</strong> 
            <span class="info-value"><?php echo esc($user['nom']); ?></span>
        </p>
        <p>
            <strong class="info-label">Email :</strong> 
            <span class="info-value"><?php echo esc($user['email']); ?></span>
        </p>
        <p>
            <strong class="info-label">Téléphone :</strong> 
            <span class="info-value"><?php echo esc($user['telephone']); ?></span>
        </p>
    </div>

    <a href="edit_profile.php" class="btn-primary">Modifier mes informations</a>
</main>


<?php include 'templates/footer.php'; ?>

<style> 
/* 
.profile-container {
    position: fixed; 
    height: 100%;       
    width: 350px;      
    z-index: 1000;
    top: 0;
    right: 0;      
    transform: translateX(351px);     
    background-color: #ffffff; 
    box-shadow: -5px 0 15px rgba(0, 0, 0, 0.15);
    padding: 30px;
    transition: transform 0.4s ease-in-out; 
}

.profile-container.sidebar-active {
    transform: translateX(0);
}


.close-btn {
    position: absolute; 
    top: 10px;
    right: 15px;
    font-size: 30px;
    color: #6c757d;
    background: none;
    border: none;
    cursor: pointer;
    line-height: 1; 
    padding: 5px;
    transition: color 0.2s;
}

.close-btn:hover {
    color: #333;
}

.profile-container h1 {
    font-size: 1.5em;
    color: #333;
    border-bottom: 2px solid #eee;
    padding-bottom: 15px;
    margin-top: 0;
    margin-bottom: 25px;
}
.profile-info p {
    display: flex;
    margin-bottom: 12px;
}

.info-label {
    font-weight: bold;
    color: #555;
    min-width: 90px;
}

.btn-primary {
    display: block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
    margin-top: 30px;
} */
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('profileSidebar'); 
    const closeBtn = document.getElementById('closeSidebarBtn'); 
    
    // IMPORTANT : On suppose que ce bouton existe ailleurs pour ouvrir la sidebar.
    const openBtn = document.getElementById('openSidebarBtn'); 

    if (!sidebar) return; 

    // Fonction centrale pour basculer (ouvrir/fermer)
    function toggleSidebar() {
        sidebar.classList.toggle('sidebar-active');
    }
    
    // 1. Écouteur pour la croix de fermeture (simplement bascule, si elle est active, elle se ferme)
    if (closeBtn) {
        closeBtn.addEventListener('click', toggleSidebar);
    }
    
    // 2. Écouteur pour le bouton d'ouverture (si vous en avez un)
    if (openBtn) {
        openBtn.addEventListener('click', function(event) {
            // ***SOLUTION DU PROBLÈME DE FERMETURE IMMÉDIATE***
            // Arrête la propagation de l'événement de clic du bouton d'ouverture.
            // Cela empêche le document.addEventListener('click', ...) de détecter
            // le même clic et de fermer la sidebar juste après son ouverture.
            event.stopPropagation(); 
            toggleSidebar();
        });
    }

    // 3. Écouteur pour fermer en cliquant en dehors de la sidebar
    document.addEventListener('click', function(event) {
        const isActive = sidebar.classList.contains('sidebar-active');
        const isClickInsideSidebar = sidebar.contains(event.target);
        
        // Ferme si elle est active et que le clic est en dehors (et n'est pas sur un autre élément géré par stopPropagation, comme openBtn)
        if (isActive && !isClickInsideSidebar) {
            sidebar.classList.remove('sidebar-active');
        }
    });
});
</script>