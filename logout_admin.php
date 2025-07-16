<?php
    session_start();

    // Détruire la session pour déconnecter l'utilisateur
    session_unset();
    session_destroy();
    
    // Rediriger vers la page de connexion ou d'accueil
    header("Location: login_admin.php");
    exit();
?>