<?php
require 'config.php';
require 'functions.php';

// Vérifier le rôle admin (même vérification que dans admin.php)
$stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([current_user_id()]);
$role = $stmt->fetchColumn();
if ($role !== 'admin') { 
    flash('Accès admin requis','danger'); 
    header('Location: index.php'); 
    exit; 
}

$errors = []; // Tableau pour stocker les erreurs de validation

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $telephone = trim($_POST['telephone'] ?? '');
    $role = $_POST['role'] ?? 'user'; // Rôle par défaut

    // Validation des données
    if (empty($nom)) { $errors[] = 'Le nom est requis.'; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Email invalide.'; }
    if (strlen($password) < 6) { $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.'; }

    // Vérifier si l'email existe déjà
    $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $check->execute([$email]);
    if ($check->fetch()) {
        $errors[] = 'Cet email est déjà utilisé.';
    }

    if (empty($errors)) {
        // Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertion dans la base de données
        $insert_stmt = $pdo->prepare(
            'INSERT INTO users (nom, email, password, telephone, role, created_at) 
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $success = $insert_stmt->execute([$nom, $email, $hashed_password, $telephone, $role]);

        if ($success) {
            flash('Utilisateur ' . esc($nom) . ' ajouté avec succès.', 'success');
            header('Location: admin.php'); // Redirection vers le panneau admin
            exit;
        } else {
            flash('Erreur lors de l\'ajout de l\'utilisateur.', 'danger');
        }
    }
}

include 'templates/header.php';
?>

<div class="table-container">
    <h2 class="admin-gestion">Admin - Ajouter un utilisateur</h2>
    
    <p><a href="admin.php" class="btn-back"> ← Retour à la gestion</a></p>

    <?php 
    // Affichage des erreurs de validation
    if (!empty($errors)) {
        echo '<div class="flash danger">';
        foreach ($errors as $error) {
            echo '<p>' . esc($error) . '</p>';
        }
        echo '</div>';
    }
    ?>

    <form method="post" class="user-form">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required value="<?php echo esc($_POST['nom'] ?? ''); ?>">

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required value="<?php echo esc($_POST['email'] ?? ''); ?>">

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <label for="telephone">Téléphone :</label>
        <input type="text" id="telephone" name="telephone" value="<?php echo esc($_POST['telephone'] ?? ''); ?>">

        <label for="role">Rôle :</label>
        <select id="role" name="role">
            <option value="user" <?php echo (($_POST['role'] ?? 'user') === 'user') ? 'selected' : ''; ?>>Utilisateur Standard</option>
            <option value="admin" <?php echo (($_POST['role'] ?? 'user') === 'admin') ? 'selected' : ''; ?>>Administrateur</option>
        </select>
        
        <button type="submit" class="btn-primary">Ajouter l'utilisateur</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>

<style>
    /* Style pour le lien "Ajouter un utilisateur" */
.add-user-link {
    font-size: 0.8em;
    padding: 5px 10px;
    background-color: #3498db;
    color: white !important;
    border-radius: 4px;
    text-decoration: none !important;
    margin-left: 15px;
    transition: background-color 0.3s;
}

.add-user-link:hover {
    background-color: #2980b9;
}

/* Style pour le formulaire d'ajout d'utilisateur */
.user-form {
    display: flex;
    flex-direction: column;
    max-width: 400px; /* Limiter la largeur du formulaire */
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: #f9f9f9;
}

.user-form label {
    margin-top: 10px;
    margin-bottom: 5px;
    font-weight: bold;
}

.user-form input[type="text"],
.user-form input[type="email"],
.user-form input[type="password"],
.user-form select {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.user-form .btn-primary {
    background-color: #2ecc71; /* Vert pour l'ajout */
    color: white;
    border: none;
    padding: 12px 20px;
    cursor: pointer;
    border-radius: 4px;
    font-size: 1em;
    transition: background-color 0.3s;
}

.user-form .btn-primary:hover {
    background-color: #27ae60;
}

.btn-back {
    display: inline-block;
    margin-bottom: 15px;
    color: #3498db;
}
</style>