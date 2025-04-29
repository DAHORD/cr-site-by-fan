<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
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
.log .links
{
  position: relative;
  width: 100%;
  display: flex;
  align-items: center;
  text-align: center;
  justify-content: center;
  padding: 0 15px;
  color: #fff;
  font-size: 21px;
}
.links2 a {
  font-size: 18px;
  padding: 10px 10px;
  text-decoration: none;
  font-weight: bold;
  color: #ffffffe0;
  border-radius: 10px;
  background: linear-gradient(135deg, #312e81, #c2185b);
  border: none;
  cursor: pointer;
}
</style>
</head>
<body>
<div class="square">
    <i style="--clr:#312e81;"></i>
    <i style="--clr:#e7c0cd;"></i>
    <i style="--clr:#c2185b;"></i>
  <div class="log">
      <h2>Bienvenue <?php echo htmlspecialchars($_SESSION['user']); ?> !</h2>
      <p>Vous êtes connecté et vous pouvez maintenant accéder à la totalité du contenu ou alors vous déconnecter :</p>
      <br>
    <div class="links2">
      <a href="../CR-killi/index.html"><b>Accéder au contenu</b></a>
    </div>
    <b><p> OU </p></b>
    <div class="links2">
      <a href="login2.html"><b>Se déconnecter</b></a>
    </div>
  </div>
</div>
</body>
</html>
