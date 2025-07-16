<?php
include 'Config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom_user'];
    $prenom = $_POST['prenom_user'];
    $nie = $_POST['nie_user'];
    $email = $_POST['email_user'];
    $classe = $_POST['classe_user'];
    $mdp = password_hash($_POST['mdp_user'], PASSWORD_DEFAULT);

    // Vérification si l'utilisateur existe déjà
    $stmt = $conn->prepare("SELECT * FROM etudiant WHERE email_etudiant = :email OR nie = :nie");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':nie', $nie);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $erreur = "Un compte avec cet email ou numéro étudiant existe déjà.";
    } else {
        // Insertion de l'utilisateur dans la base de données
        $stmt = $conn->prepare("INSERT INTO etudiant (nom_etudiant, prenom_etudiant, nie, email_etudiant, classe_etudiant, mdp_etudiant) VALUES (:nom, :prenom, :nie, :email, :classe, :mdp)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nie', $nie);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':classe', $classe);
        $stmt->bindParam(':mdp', $mdp);

        if ($stmt->execute()) {
            header("Location: accueil.php");
            exit();
        } else {
            $erreur = "Erreur lors de l'inscription. Veuillez réessayer.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription - ESMIA</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        :root {
            --bg-light: #1c1e26;
            --card-bg: #2a2e38;
            --accent: #ffffff;
            --text-secondary: #adb5bd;
            --primary-green: #4caf50;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--accent);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .section-inscription {
            background-color: var(--card-bg);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }

        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .form-header h2 {
            font-size: 1.4rem;
            color: var(--text-secondary);
            margin-bottom: 5px;
        }

        .form-header h1 {
            font-size: 1.8rem;
            color: var(--accent);
            border-bottom: 2px solid var(--primary-green);
            padding-bottom: 5px;
        }

        .inscr-input-box {
            margin-bottom: 20px;
        }

        .inscr-input-box input {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 6px;
            background-color: #1e212b;
            color: white;
            font-size: 1rem;
        }

        .inscr-input-box input::placeholder {
            color: #888;
        }

        .sign-up-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-green);
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .sign-up-btn:hover {
            background-color: #45a043;
        }

        .connexion-link {
            text-align: center;
            margin-top: 15px;
        }

        .connexion-link a {
            color: var(--primary-green);
            text-decoration: none;
        }

        .connexion-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 400px) {
            .section-inscription {
                padding: 30px 20px;
            }

            .section-inscription h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="section-inscription">
        <form method="post">
            <div class="form-header">
                <h1>ESMIA Voting</h1>
                <h2>Inscription</h2>
            </div>

            <div class="inscr-input-box">
                <input type="text" placeholder="Nom" name="nom_user" required>
            </div>

            <div class="inscr-input-box">
                <input type="text" placeholder="Prénom" name="prenom_user" required>
            </div>

            <div class="inscr-input-box">
                <input type="text" placeholder="Numéro étudiant" name="nie_user" required>
            </div>

            <div class="inscr-input-box">
                <input type="email" placeholder="Email" name="email_user" required>
            </div>

            <div class="inscr-input-box">
                <input type="text" placeholder="Classe" name="classe_user" required>
            </div>

            <div class="inscr-input-box">
                <input type="password" placeholder="Mot de passe" name="mdp_user" required>
            </div>

            <button type="submit" class="sign-up-btn">S'inscrire</button>
        </form>
        <?php if (!empty($erreur)): ?>
            <div style="color: #ff6b6b; text-align: center; margin-bottom: 15px;">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>
        <div class="connexion-link">
        <p>Compte déjà existant ? <a href="login.php">Connexion</a></p>
        </div>
    </div>

</body>

</html>