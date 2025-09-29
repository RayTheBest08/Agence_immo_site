<?php
require 'config.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    
    // Vérifier le rôle de l'utilisateur
    $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
    $stmt->execute([$user['id']]);
    $role = $stmt->fetchColumn();
    flash('Connexion réussie', 'success');
    if ($role === 'admin') {
      header('Location: admin.php'); exit;
    } else {
      header('Location: dashboard.php'); exit;
    }
  } else {
    flash('Identifiants invalides', 'danger');
  }

if ($role === 'admin') {
    header('Location: admin.php'); exit;
} else {
    header('Location: dashboard.php'); exit;
}

}
include 'templates/header.php';
?>


    <form method="post" class="register-form" style=" align-item:center; width: 350px; margin: auto;  margin-top: 1rem;  padding: 20px; border-radius: 20px; background-color: #ffffff1e;">
      <div class="text-center mb-4">
        <h2 style="width: 100%; color: #000000; font-size: xx-large; ">Connexion</h2>
      </div>
  <div class="form-group ">
    <label for="Email" class="form-label" style="font-size: 14px;margin-bottom: 5px;display: block;color: #000000;">Adresse email</label>
    <input type="email" name="email" class="form-control" id="Email" aria-describedby="emailHelp" placeholder="Entrez votre email">
  </div>
  <div class="form-group ">
    <label for="Password" class="form-label" style="font-size: 14px;margin-bottom: 5px;display: block;color: #000000;">Mot de passe</label>
    <input type="password" name="password" class="form-control" id="Password" placeholder="Entrez votre mot de passe" style="font-size: 14px;margin-bottom: 5px;display: block;color: #000000;">
  </div>
  <div class="form-group ">
   <P><a href=".php">mot de passe oublié</a> <span style="width: 100%; color: #000000;">ou</span> <a href="register.php">créer un compte</a></P>
    </div>
      
  </div>

  <button type="submit" name="valider" style.display = 'none'; class="btn btn-primary">Connexion</button>
</form>
<?php include 'templates/footer.php'; ?>
