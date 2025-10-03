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

<main class="dashboard-container">
    <h1 class="dashboard-title">Mon Dashboard</h1>

    <section class="dashboard-section create-annonce">
        <h2 class="section-title">Créer une annonce</h2>
        <form method="post" enctype="multipart/form-data" class="annonce-form">
            <div class="form-group">
                <input name="titre" required placeholder="Titre de l'annonce" class="form-input">
            </div>
            <div class="form-group">
                <textarea name="description" required placeholder="Description détaillée" class="form-textarea"></textarea>
            </div>
            <div class="form-group form-inline">
                <input name="prix" required placeholder="Prix" class="form-input price-input">
                <input name="ville" placeholder="Ville" class="form-input city-input">
            </div>
            <div class="form-group form-inline">
                <select name="category_id" class="form-select">
                    <option value="1">Propriété</option>
                    <option value="2">Voiture</option>
                    <option value="3">Terrain</option>
                </select>
                <select name="type_transaction_id" class="form-select">
                    <option value="1">Vente</option>
                    <option value="2">Location</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label-file">
                    Ajouter une image :
                    <input type="file" name="image" class="form-file">
                </label>
            </div>
            <button name="create_annonce" type="submit" class="btn btn-primary">Publier l'annonce</button>
        </form>
    </section>

    <hr>

    <section class="dashboard-section my-annonces">
        <h2 class="section-title">Mes annonces</h2>
        <div class="annonce-list">

            <?php foreach($my_annonces as $a): ?>
                <div class="annonce-item">
                    <h3 class="annonce-title"><?php echo esc($a['titre']); ?></h3>
                    <p class="annonce-description"><?php echo esc($a['description']); ?></p>
                    <a href="/property.php?id=<?php echo $a['id']; ?>" class="btn btn-secondary">Voir l'annonce</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include 'templates/footer.php'; ?>
<style>

:root {
    --primary-color: #007bff; /* Bleu pour les actions principales */
    --secondary-color: #6c757d; /* Gris pour les actions secondaires */
    --background-light: #f8f9fa; /* Arrière-plan clair */
    --card-background: #ffffff; /* Arrière-plan des sections/cartes */
    --text-dark: #343a40; /* Texte principal foncé */
    --border-color: #dee2e6;
    --box-shadow-light: 0 0.5rem 1rem rgba(0, 0, 0, 0.05); /* Ombre douce */
}

body {
    font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
    background-color: var(--background-light);
    color: var(--text-dark);
    margin: 0;
    padding: 0;
}

/* --- Structure du Dashboard --- */
.dashboard-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
}

.dashboard-title {
    font-size: 2.5em;
    color: var(--primary-color);
    margin-bottom: 30px;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 15px;
}

.dashboard-section {
    background-color: var(--card-background);
    padding: 30px;
    margin-bottom: 30px;
    border-radius: 8px;
    box-shadow: var(--box-shadow-light);
}

.section-title {
    font-size: 1.8em;
    color: var(--text-dark);
    margin-top: 0;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--border-color);
}

/* --- Formulaire de Création --- */
.annonce-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-group {
    margin-bottom: 10px;
}

.form-inline {
    display: flex;
    gap: 15px;
}

.form-inline .form-input,
.form-inline .form-select {
    flex-grow: 1;
}

.form-input,
.form-textarea,
.form-select {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    box-sizing: border-box; /* Assure que padding et border sont inclus dans la largeur/hauteur */
    font-size: 1em;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

/* --- Fichier Input (Masquage de l'input brut) --- */
.form-label-file {
    display: block;
    padding: 10px 15px;
    cursor: pointer;
    background-color: var(--secondary-color);
    color: var(--card-background);
    border-radius: 4px;
    text-align: center;
    transition: background-color 0.3s;
}

.form-label-file:hover {
    background-color: #5a6268;
}

.form-file {
    /* Masquer l'input de fichier par défaut */
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}

/* --- Boutons --- */
.btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 4px;
    font-size: 1em;
    text-align: center;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: background-color 0.3s, opacity 0.3s;
}

.btn-primary {
    background-color: var(--primary-color);
    color: var(--card-background);
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: var(--secondary-color);
    color: var(--card-background);
    padding: 8px 15px; /* Rendre le bouton 'Voir' plus petit */
}

.btn-secondary:hover {
    background-color: #545b62;
}

/* --- Liste des Annonces --- */
.annonce-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Responsive grid */
    gap: 20px;
}

.annonce-item {
    border: 1px solid var(--border-color);
    padding: 20px;
    border-radius: 6px;
    background-color: var(--card-background);
    display: flex;
    flex-direction: column;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s, box-shadow 0.2s;
}

.annonce-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.annonce-title {
    font-size: 1.25em;
    color: var(--primary-color);
    margin-top: 0;
    margin-bottom: 10px;
}

.annonce-description {
    color: var(--secondary-color);
    flex-grow: 1; /* Pousse le bouton vers le bas */
    margin-bottom: 15px;
}

.annonce-item .btn-secondary {
    align-self: flex-start; /* Aligne le bouton à gauche dans la carte */
}

/* --- Séparateur --- */
hr {
    border: 0;
    height: 1px;
    background: var(--border-color);
    margin: 30px 0;
}</style>