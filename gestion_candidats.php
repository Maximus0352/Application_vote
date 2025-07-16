<?php
    session_start();
    require_once '../Config.php';

    if (!isset($_SESSION['admin_id'])) {
        header("Location: login_admin.php");
        exit();
    }

    // Récupérer toutes les candidatures en attente, validées ou rejetées
    $stmt = $conn->prepare("
        SELECT 
            c.id_candidature, c.slogan_candidat AS slogan, c.programme_candidat AS programmes, 
            c.photo_candidat AS photo, c.statut_validation,
            e.nom_etudiant, e.prenom_etudiant, e.classe_etudiant
        FROM CANDIDATURES c
        JOIN ETUDIANTS e ON c.id_etudiant = e.id_etudiant
        ORDER BY c.date_depot DESC
    ");

    $stmt->execute();
    $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestion des Candidats - ESMIA</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    :root {
        --bg-dark: #12141b;
        --bg-light: #1c1e26;
        --card-bg: #2a2e38;
        --accent: #ffffff;
        --text-secondary: #adb5bd;
        --primary-green: #4caf50;
        --danger: #dc3545;
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
        width: 16px;
        height: 16px;
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

    .header-page h2 {
        font-size: 1.5rem;
        margin-bottom: 30px;
        text-align: center;
    }

    .candidature-list {
        display: grid;
        gap: 20px;
    }

    .candidature {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    }

    .candidature-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .candidature h3 {
        margin-bottom: 10px;
    }

    .candidature p {
        font-size: 0.95rem;
        color: var(--text-secondary);
    }

    .btns {
        margin-top: 15px;
        display: flex;
        gap: 10px;
    }

    .btn-validate,
    .btn-refuse {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 0.9rem;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-validate {
        background-color: var(--primary-green);
    }

    .btn-validate:hover {
        background-color: #45a043;
    }

    .btn-refuse {
        background-color: var(--danger);
    }

    .btn-refuse:hover {
        background-color: #bb2d3b;
    }

    @media (max-width: 768px) {
        .body-content {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            height: auto;
            position: fixed;
            top: 0;
            left: 0;
            flex-direction: row;
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
    <!-- Sidebar -->
    <nav class="sidebar">
      <div class="logo">ESMIA Admin</div>
      <div class="menu">
        <a href="dashboard_admin.php">
          <svg xmlns="http://www.w3.org/2000/svg" class="dash" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h6v6H3V3zM15 3h6v6h-6V3zM3 15h6v6H3v-6zM15 15h6v6h-6v-6z" />
          </svg>
          <span>&nbsp;Dashboard</span>
        </a>
        <a href="gestion_candidats.php" class="active"><i class="fas fa-user-tie"></i><span>&nbsp;Candidatures</span></a>
        <a href="gestion_scrutin.php"><i class="fas fa-calendar-alt"></i><span>&nbsp;Scrutins</span></a>
        <a href="logout_admin.php"><i class="fas fa-sign-out-alt"></i><span>&nbsp;Déconnexion</span></a>
      </div>
    </nav>

    <!-- Contenu -->
    <div class="container">
        <header class="header-page">
            <h2>Gestion des candidatures</h2>
        </header>

        <div class="candidature-list">
            <?php foreach ($candidatures as $candidat): ?>
                <div class="candidature">
                    <div class="candidature-header">
                        <h3><?= htmlspecialchars($candidat['prenom_etudiant'] . " " . $candidat['nom_etudiant']) ?></h3>
                        <span class="badge"><?= htmlspecialchars($candidat['classe_etudiant']) ?></span>
                    </div>
                    <p><strong>Slogan :</strong> <?= htmlspecialchars($candidat['slogan']) ?></p>
                    <p><strong>Programmes :</strong> <?= nl2br(htmlspecialchars($candidat['programmes'])) ?></p>
                    <img src="../uploads/<?= $candidat['photo'] ?>" width="80">

                    <?php if ($candidat['statut_validation'] === 'en attente'): ?>
                        <form method="post" action="traitement_validation.php" class="btns">
                            <input type="hidden" name="id_candidat" value="<?= $candidat['id_candidature'] ?>">
                            <button name="validen" value="validée" class="btn-validate">Valider</button>
                            <button name="refuser" value="rejetée" class="btn-refuse">Refuser</button>
                        </form>
                    <?php else: ?>
                        <p><strong>Statut :</strong> 
                            <?php if ($candidat['statut_validation'] === 'validée'): ?>
                                <span style="color: #4caf50;">✅ Validée</span>
                            <?php elseif ($candidat['statut_validation'] === 'rejetée'): ?>
                                <span style="color: #f44336;">❌ Rejetée</span>
                            <?php else: ?>
                                <span style="color: #ffc107;">⏳ En attente</span>
                            <?php endif; ?>
                    </p>

                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
  </div>
</body>
</html>