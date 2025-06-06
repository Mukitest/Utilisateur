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
    $login = trim($_POST['login'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
    $role = ($_POST['role'] ?? 'utilisateur');
    $email = trim($_POST['email'] ?? '');

    if (!empty($login) && !empty($mot_de_passe)) {
        // Vérifier si l'utilisateur existe déjà
        $check = $pdo->prepare("SELECT * FROM Utilisateurs WHERE login = :login");
        $check->execute(['login' => $login]);

        if ($check->fetch()) {
            $_SESSION['register_error'] = "Ce login existe déjà.";
            header('Location: create_users.php');
            exit();
        }
        
        // Hash du mot de passe
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Insertion dans la BDD
        $stmt = $pdo->prepare("INSERT INTO Utilisateurs (login, mot_de_passe, role, email) VALUES (:login, :mot_de_passe, :role, :email)");
        $stmt->execute([
            'login' => $login,
            'mot_de_passe' => $hash,
            'email'=> $email,
            'role' => $role
        ]);

        $_SESSION['register_success'] = "Création réussie. Vous pouvez vous connecter.";
        header('Location: adminPanel.php');
        exit();
    } else {
        $_SESSION['register_error'] = "Veuillez remplir tous les champs.";
        header('Location: create_users.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Creation admin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .form-container { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
        h2 { text-align: center; margin-bottom: 20px; }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { width: 100%; padding: 10px; background: #00b3e6; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #009ac4; }
        .message { color: red; font-size: 14px; margin-bottom: 10px; text-align: center; }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Créer un utilisateur</h2>
        <form action="" method="POST">
            <input type="text" name="login" placeholder="Nom d'utilisateur" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <input type="email" name="email" placeholder="Email" required>
            <select name="role" readonly>
                <option value="utilisateur" selected> Utilisateur </option>
            </select>
            <button type="submit" class="btn">Créer l'utilisateur</button>
        </form>

        <?php
        if (isset($_SESSION['register_error'])) {
            echo '<div class="message">' . $_SESSION['register_error'] . '</div>';
            unset($_SESSION['register_error']);
        }
        ?>
    </div>
</body>
</html>