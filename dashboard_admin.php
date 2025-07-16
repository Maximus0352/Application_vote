<?php
    session_start();
    require_once '../Config.php';

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login_admin.php");
        exit();
    }

    // Nombre total d'étudiants
    $stmt = $conn->query("SELECT COUNT(*) FROM ETUDIANTS");
    $nbEtudiants = $stmt->fetchColumn();

    // Nombre de candidats validés
    $stmt = $conn->query("SELECT COUNT(*) FROM CANDIDATURES WHERE statut_validation = 'validée'");
    $nbCandidatsValides = $stmt->fetchColumn();

    // Nombre de votes
    $stmt = $conn->query("SELECT COUNT(*) FROM VOTE");
    $nbVotes = $stmt->fetchColumn();

    // Nombre de scrutins actifs
    $stmt = $conn->query("SELECT COUNT(*) FROM SCRUTINS WHERE NOW() BETWEEN date_heure_debut AND date_heure_fin");
    $nbScrutinsActifs = $stmt->fetchColumn();
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tableau de bord - Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    :root {
        --bg-dark: #12141b;
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
    }

    a {
        text-decoration: none;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        padding: 15px;
        transition: background 0.3s;
    }

    a.active {
        color: var(--primary-green);
    }

    a:hover {
        background-color: #343a40;
        border-radius: 15px;
        color: #fff;
    }

    .dash {
        width: 18px;
        height: 18px;
    }

    .body-content {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 250px;
        background-color: var(--bg-dark);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
    }

    .logo {
        text-align: center;
        padding: 20px;
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--accent);
    }

    .menu {
        display: flex;
        flex-direction: column;
    }

    .container {
        flex: 1;
        padding: 40px;
        margin-left: 250px;
    }

    .dashboard-grid {
        margin-top: 30px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .card {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    }

    .card i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: var(--primary-green);
    }

    .card h3 {
        font-size: 1.1rem;
        margin-bottom: 5px;
        color: var(--text-secondary);
    }

    .card p {
        font-size: 1.7rem;
        font-weight: bold;
        color: var(--accent);
    }

    @media (max-width: 768px) {
        .body-content {
                flex-direction: column;
        }

        .sidebar {
            width: 100%;
            height: auto;
            flex-direction: row;
            position: fixed;
            top: 0;
            left: 0;
        }

        .menu {
            flex-direction: row;
            justify-content: space-around;
            width: 100%;
        }

        .menu a {
            padding: 10px;
            font-size: 13px;
            flex: 1;
            justify-content: center;
        }

        .container {
            margin-left: 0;
            margin-top: 80px;
            padding: 20px;
        }
    }

    @media (max-width: 580px) {
        .menu a span {
            display: none;
        }

        .menu a {
            justify-content: center;
        }

        .menu i {
            margin-right: 0;
        }
    }
  </style>
</head>

<body>
    <div class="body-content">
        <!-- Menu -->
        <nav class="sidebar">
            <div class="logo">ESMIA Admin</div>
            <div class="menu">
                <a href="dashboard_admin.php" class="active">
                    <svg xmlns="http://www.w3.org/2000/svg" class="dash" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3V3zM15 3h6v6h-6V3zM3 15h6v6H3v-6zM15 15h6v6h-6v-6z"/>
                    </svg>
                    <span>&nbsp;Dashboard</span>
                </a>
                <a href="gestion_candidats.php"><i class="fas fa-user-tie"></i><span>&nbsp;Candidatures</span></a>
                <a href="gestion_scrutin.php"><i class="fas fa-calendar-alt"></i><span>&nbsp;Scrutins</span></a>
                <a href="logout_admin.php"><i class="fas fa-sign-out-alt"></i><span>&nbsp;Déconnexion</span></a>
            </div>
        </nav>

        <!-- Contenu -->
        <div class="container">
            <section class="dashboard-grid">
                <div class="card">
                    <i class="fas fa-users"></i>
                    <h3>Étudiants inscrits</h3>
                    <p><?= $nbEtudiants ?></p>
                </div>

                <div class="card">
                    <i class="fas fa-user-check"></i>
                    <h3>Candidats validés</h3>
                    <p><?= $nbCandidatsValides ?></p>
                </div>

                <div class="card">
                    <i class="fas fa-vote-yea"></i>
                    <h3>Votes enregistrés</h3>
                    <p><?= $nbVotes ?></p>
                </div>

                <div class="card">
                    <i class="fas fa-calendar-check"></i>
                    <h3>Scrutins actifs</h3>
                    <p><?= $nbScrutinsActifs ?></p>
                </div>
            </section>
        </div>
    </div>
</body>

</html>