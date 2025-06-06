<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'authpage';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

    // Hash du mot de passe
    $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Vérifier si l'email existe
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Mise à jour du mot de passe
    $stmt_update = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = :hash WHERE email = :email");
    $stmt_update->bindParam(':hash', $hash);
    $stmt_update->bindParam(':email', $email);

    if ($stmt_update->execute()) {
        $_SESSION['updateMSG_success'] = "Mot de passe mis à jour avec succès.";
        header('Location: index.php');
    } else {
        $_SESSION['updateMSG_error'] = "Erreur lors de la mise à jour du mot de passe.";
        header('Location: index.php');
    }
} else {
    $_SESSION['mailMSG_error'] ="Adresse e-mail introuvable.";
    header('Location: index.php');
}

}