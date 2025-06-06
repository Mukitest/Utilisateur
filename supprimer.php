<?php
session_start();

// Vérifie que l'utilisateur est connecté et est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Vérifie que l'ID est fourni et valide
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID utilisateur invalide.";
    header("Location: adminPanel.php");
    exit();
}

$id_utilisateur = (int) $_GET['id'];

// Empêche un admin de se supprimer lui-même
if ($id_utilisateur === $_SESSION['user_id']) {
    $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
    header("Location: adminPanel.php");
    exit();
}

// Connexion à la base de données
$host = 'localhost';
$dbname = 'authpage';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Suppression
    $stmt = $pdo->prepare("DELETE FROM Utilisateurs WHERE id = :id");
    $stmt->execute([':id' => $id_utilisateur]);

    $_SESSION['message'] = "Utilisateur supprimé avec succès.";
    header("Location: adminPanel.php");
    exit();
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de la suppression : " . $e->getMessage();
    header("Location: adminPanel.php");
    exit();
}
?>
