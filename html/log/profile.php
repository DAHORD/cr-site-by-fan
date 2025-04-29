<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

try {
    // Connexion à la base de données SQLite
    $db = new PDO('sqlite:database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations de l'utilisateur à partir de son nom d'utilisateur
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // S'il n'y a pas d'utilisateur correspondant (cas improbable), rediriger vers la page de connexion
    if (!$user) {
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Erreur : " . $e->getMessage();
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
  <style>
    body
    {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 100vh;
        background: #151f28;
        color: #fff;
        font-size: 20px;
        a{
            color: #e7c0cd;
        }
    }
    br {
        content: "";
        display: block;
        margin-bottom: 15px; 
    }
    .info {
      text-align: left;
      margin: 10px 0;
    }
    .info p {
      margin: 8px 0;
    }
    .info label {
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="square">
    <i style="--clr:#312e81;"></i>
    <i style="--clr:#e7c0cd;"></i>
    <i style="--clr:#c2185b;"></i>
  <div class="log">
    <h2>Mon Profil</h2>
    <?php if (isset($error)) { ?>
      <p style="color: red;"><?php echo $error; ?></p>
    <?php } else { ?>
      <div class="info">
        <br>
        <p><label>Nom d'utilisateur :</label> <?php echo htmlspecialchars($user['username']); ?></p>
        <br>
        <p><label>Email :</label> <?php echo htmlspecialchars($user['email']); ?></p>
        <br>
        <p><label>Date d'inscription :</label> <?php echo htmlspecialchars($user['created_at']); ?></p>
        <br>
      </div>
      <p><a href="logout.php">Déconnexion</a></p>
    <?php } ?>
  </div>
</div>
</body>
</html>
