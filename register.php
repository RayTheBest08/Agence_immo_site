<?php
require 'config.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'user';

    if (!$nom || !$email || !$password) {
        flash('Tous les champs obligatoires doivent être remplis', 'danger');
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            flash('Email déjà utilisé', 'danger');
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (nom,email,password,telephone,role,created_at) VALUES (?,?,?,?,?,NOW())');
            $stmt->execute([$nom,$email,$hash,$telephone,$role]);
            flash('Inscription réussie. Connectez-vous.', 'success');
            header('Location: login.php'); exit;
        }
    }
}
include 'templates/header.php';
?>


<form method="post" class="register-form">
  <h2 >Créer un compte</h2>
  <div  class="form-group " >
     <label for="nom">Nom</label> 
     <input type="text" name="nom" id="Nom" placeholder="Entrez votre nom complet">
  </div>
  <div  class="form-group ">
     <label for="telephone">Téléphone</label>
     <input type="text" name="telephone" id="telephone" placeholder="Entrez votre numéro">
  </div>
  <div   class="form-group ">
     <label for="role">Rôle</label>
     <select name="role" id="role">
      <option value="user">Client</option>
      <option value="owner">Propriétaire</option>
      <!-- <option value="admin">Admin</option> -->

    </select>
  </div>
  <div  class="form-group ">
     <label for="email">Adresse email</label>
     <input type="email" name="email" id="Email" placeholder="Entrez votre email">
  </div>
  <div   class="form-group ">
     <label for="Password">Mot de passe</label>
     <input type="password" name="password" id="Password" placeholder="Entrez votre mot de passe">
  </div>
  <div style="margin-bottom: 18px;">
     <label for="ConfirmPassword">Confirmer le mot de passe</label>
     <input type="password" id="ConfirmPassword" placeholder="Confirmez votre mot de passe">
  </div>
    <button type="submit" name="submit">S'inscrire</button>
    <div class="register-links">
     <a href="#">mot de passe oublié ?</a>
     <a href="login.php">Connexion</a>
    </div>
  </form>
</form>


<?php include 'templates/footer.php'; ?>
