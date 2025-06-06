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
    error_log($e->getMessage(), 3, __DIR__ . '/logs/db_error.log');
    die("Une erreur est survenue. Veuillez réessayer plus tard.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = htmlspecialchars(trim($_POST['login'] ?? ''));
    $mot_de_passe = htmlspecialchars(trim($_POST['mot_de_passe'] ?? ''));

    if (!empty($login) && !empty($mot_de_passe)) {
        $stmt = $pdo->prepare("SELECT * FROM Utilisateurs WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['login'] = $login;
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            // Facultatif : stocker d'autres infos
            $_SESSION['user'] = [
                'login' => $user['login'],
                'email' => $user['email'], // Vérifie que cette colonne existe
                'role' => $user['role']
            ];

            if ($user['role'] === 'admin') {
                header('Location: adminPanel.php');
            } elseif ($user['role'] === 'utilisateur') {
                header('Location: utilisateur.php');
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Nom d'utilisateur ou mot de passe incorrect.";
            header('Location: index.php');
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Veuillez remplir tous les champs.";
        header('Location: index.php');
        exit();
    }
}
?>
