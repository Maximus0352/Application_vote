<?php
include '../Config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom_admin'];
    $email = $_POST['email_admin'];
    $mdp = password_hash($_POST['mdp_admin'], PASSWORD_DEFAULT);

    // Vérification si l'admin existe déjà
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email_admin = :email_admin");
    $stmt->bindParam(':email_admin', $email);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $erreur = "Un compte avec cet email existe déjà.";
    } else {
        // Insertion de l'admin dans la base de données
        $stmt = $conn->prepare("
            INSERT INTO admin (id_admin, nom_admin, email_admin, mdp_admin)
            VALUES (1, nom_admin, email_admin, mdp_admin)
        ");
        $stmt->bindParam('nom_admin', $nom);
        $stmt->bindParam('email_admin', $email);
        $stmt->bindParam('mdp_admin', $mdp);

        if ($stmt->execute()) {
            header("Location: login_admin.php");
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
                <h1>Administration</h1>
                <h2>Inscription</h2>
            </div>

            <div class="inscr-input-box">
                <input type="text" placeholder="Nom" name="nom_admin" required>
            </div>

            <div class="inscr-input-box">
                <input type="email" placeholder="Email" name="email_admin" required>
            </div>

            <div class="inscr-input-box">
                <input type="password" placeholder="Mot de passe" name="mdp_admin" required>
            </div>

            <button type="submit" class="sign-up-btn">S'inscrire</button>
        </form>
        <div class="connexion-link">
            <p>Compte déjà existant ? <a href="login_admin.php">Connexion</a></p>
        </div>
    </div>

</body>

</html>