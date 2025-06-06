<?php
session_start();

// Vérifie si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
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
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}

// Récupération de l'utilisateur à modifier via l'ID passé en GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID utilisateur invalide.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $nouveau_mdp = trim($_POST['mot_de_passe'] ?? '');

    if (!empty($email)) {
        // Si un nouveau mot de passe est saisi, on le met à jour aussi
        if (!empty($nouveau_mdp)) {
            $mdp_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE Utilisateurs SET email = :email, mot_de_passe = :mdp WHERE Id = :Id");
            $stmt->execute([
                ':email' => $email,
                ':mdp' => $mdp_hash,
                ':Id' => $id
            ]);
        } else {
            // Sinon on ne met à jour que l'email
            $stmt = $pdo->prepare("UPDATE Utilisateurs SET email = :email WHERE id = :id");
            $stmt->execute([
                ':email' => $email,
                ':id' => $id
            ]);
        }

        $success = "Utilisateur mis à jour avec succès.";
    } else {
        $error = "L'email ne peut pas être vide.";
    }
}

// Récupération des données actuelles de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE id = :id");
$stmt->execute([':id' => $id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    die("Utilisateur introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier utilisateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-white">
<div class="container mt-5">
    <h2>Modifier l'utilisateur : <?= htmlspecialchars($utilisateur['login']) ?></h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Nouvel Email (Celui afficher ci-dessous est l'actuel')</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utilisateur['email'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label for="mot_de_passe" class="form-label">Nouveau mot de passe (laisser vide pour ne pas modifier)</label>
            <input type="password" name="mot_de_passe" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="adminPanel.php" class="btn btn-secondary">Retour</a>
    </form>
</div>
</body>
</html>
