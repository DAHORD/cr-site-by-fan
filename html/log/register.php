<?php
// Démarrage de la session pour d'éventuelles utilisations futures
session_start();

// Vérification que le formulaire a bien été soumis en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyage et récupération des données
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $email    = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = trim($_POST['password']);

    // Vérification que tous les champs sont remplis
    if (empty($username) || empty($email) || empty($password)) {
        die("Tous les champs sont obligatoires.");
    }

    // Connexion à la base de données SQLite
    // Le fichier 'database.sqlite' sera créé automatiquement s'il n'existe pas
    try {
        $db = new PDO('sqlite:database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Création de la table 'users' au cas où elle n'existerait pas déjà
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $db->exec($sql);

        // Hachage du mot de passe pour la sécurité
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Préparation de la requête d'insertion en utilisant une requête préparée pour éviter les injections SQL
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        echo "Inscription réussie !";
    } catch (PDOException $e) {
        // En cas d'erreur (par exemple, si le nom d'utilisateur ou l'email existe déjà), on affiche le message d'erreur
        echo "Erreur : " . $e->getMessage();
    }
}
else {
    // Si le script est accédé directement, on redirige vers le formulaire
    header("Location: register.html");
    exit();
}
?>
