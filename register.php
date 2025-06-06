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
            header('Location: register_form.php');
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

        $_SESSION['register_success'] = "Inscription réussie. Vous pouvez vous connecter.";
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['register_error'] = "Veuillez remplir tous les champs.";
        header('Location: register_form.php');
        exit();
    }
}
?>