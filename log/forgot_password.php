<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer et nettoyer l'email
    $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));

    if (empty($email)) {
        die("L'adresse email est requise.");
    }

    try {
        // Connexion à la base SQLite (utilisée précédemment pour l'inscription)
        $db = new PDO('sqlite:database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier l'existence d'un utilisateur avec cette adresse email
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // On prépare toujours la réponse pour l'utilisateur afin de ne pas divulguer l'existence ou non du compte
        $messageSuccess = "Si un compte correspondant à cette adresse existe, un email avec les instructions de réinitialisation a été envoyé.";

        if ($user) {
            // Générer un token aléatoire de 32 caractères (16 octets convertis en hexadécimal)
            $token   = bin2hex(random_bytes(16));
            $expires = time() + 3600; // Le token expire dans 1 heure

            // Créer la table password_resets si elle n'existe pas déjà
            $sql = "CREATE TABLE IF NOT EXISTS password_resets (
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        user_id INTEGER NOT NULL,
                        token TEXT NOT NULL,
                        expires INTEGER NOT NULL,
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (user_id) REFERENCES users(id)
                    )";
            $db->exec($sql);

            // Insertion du token pour l'utilisateur
            $stmt2 = $db->prepare("INSERT INTO password_resets (user_id, token, expires) VALUES (:user_id, :token, :expires)");
            $stmt2->bindParam(":user_id", $user["id"], PDO::PARAM_INT);
            $stmt2->bindParam(":token", $token);
            $stmt2->bindParam(":expires", $expires, PDO::PARAM_INT);
            $stmt2->execute();

            // Générer un lien de réinitialisation (à adapter avec votre domaine et vos routes)
            $resetLink = "http://votre-domaine/reset_password.php?token=" . $token;
            $emailMessage = "Bonjour,\n\nPour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant :\n" . $resetLink . "\n\nSi vous n'avez pas fait cette demande, ignorez cet email.";

            // En production, utilisez la fonction mail() ou une librairie pour envoyer cet email.
            // Exemple : mail($email, "Réinitialisation du mot de passe", $emailMessage);
        }

        // Afficher toujours le même message pour l'utilisateur, qu'un compte soit trouvé ou non
        echo $messageSuccess;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    header("Location: forgot_password.html");
    exit();
}
?>
