<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données envoyées
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $password = trim($_POST['password']);

    // Vérification que les champs ne sont pas vides
    if (empty($username) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            // Connexion à la base de données SQLite (le même fichier utilisé pour l'inscription)
            $db = new PDO('sqlite:database.sqlite');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparation de la requête pour récupérer l'utilisateur par nom d'utilisateur
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si l'utilisateur existe et que le mot de passe correspond
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['username'];
                header("Location: dashboard.php"); // Redirection vers une page protégée
                exit();
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style1.css">
  <title>Log Page</title>
</head>
<body>
  <div class="square">
    <i style="--clr:#312e81;"></i>
    <i style="--clr:#e7c0cd;"></i>
    <i style="--clr:#c2185b;"></i>
    <div class="log">
      <h2>Login</h2>
      <form action="login.php" method="post">
        <div class="inputBx">
          <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <br><br>
        </div>
        <div class="inputBx">
          <input type="password" name="password" placeholder="Mot de passe" required>
        <br><br>
        </div>
        <div class="inputBx">
          <input type="submit" value="Se connecter">
        <br><br>
        </div>
      </form>
      <a href="forgot_password.html">Mot de passe oublié ?</a>
      <br>
      <div class="links">
        <p>Pas encore de compte ?</p>
        <a href="register.html">S'inscrire</a>
      </div>
    </div>
  </div>
    <?php if (isset($error)) { echo '<p class="error">' . $error . '</p>'; } ?>
  </div>
</body>
</html>
