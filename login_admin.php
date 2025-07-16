<?php
session_start();

// Rediriger vers le tableau de bord si déjà connecté
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard_admin.php");
    exit();
}

require '../Config.php';
$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email_admin'] ?? '';
    $mdp = $_POST['mdp_admin'] ?? '';

    // Requête de vérification
    $stmt = $conn->prepare("SELECT id_admin, mdp_admin FROM admin WHERE email_admin = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($mdp, $admin['mdp_admin'])) {
        // Authentification réussie
        $_SESSION['id_admin'] = $admin['id_admin'];
        header("Location: dashboard_admin.php");
        exit();
    } else {
        $erreur = "❌ Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion - ESMIA</title>

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

        .section-connexion {
            background-color: var(--card-bg);
            padding: 40px 30px;
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

        .cennex-input-box {
            margin-bottom: 20px;
        }

        .cennex-input-box input {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 6px;
            background-color: #1e212b;
            color: white;
            font-size: 1rem;
        }

        .cennex-input-box input::placeholder {
            color: #888;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            margin-bottom: 20px;
        }

        .remember-forgot label {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .remember-forgot a {
            color: var(--primary-green);
            text-decoration: none;
        }

        .remember-forgot a:hover {
            text-decoration: underline;
        }

        .login-btn {
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

        .login-btn:hover {
            background-color: #45a043;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .register-link a {
            color: var(--primary-green);
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 400px) {
            .section-connexion {
                padding: 30px 20px;
            }

            .section-connexion h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="section-connexion">
        <form action="#" method="post">
            <div class="form-header">
                <h1>Administration</h1>
                <h2>Connexion</h2>
            </div>

            <div class="cennex-input-box">
            <input type="email" placeholder="Email" name="email_admin" required>
            </div>

            <div class="cennex-input-box">
            <input type="password" placeholder="Mot de passe" name="mdp_admin" required>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox"> Se souvenir de moi</label>
                <a href="#">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="login-btn">Se connecter</button>

        </form>
        <div class="register-link">
            <p>Pas de compte ? <a href="register_admin.php">Inscription</a></p>
        </div>
    </div>

</body>

</html>