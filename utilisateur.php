<?php session_start(); 
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Utilisateur</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&family=Open+Sans:wght@300;400;600&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Global */
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #2d2d72, #00bcd4);
            color: #fff;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            animation: backgroundEffect 15s ease-in-out infinite;
            font-size: 14px;
        }
 
        /* Header */
        header {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 40px 0;
            text-align: center;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
            border-bottom: 2px solid #00bcd4;
        }
 
        header h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 3.5rem;
            color: #00e6e6;
            text-shadow: 0 0 12px rgba(0, 255, 255, 0.8);
            margin: 0;
            letter-spacing: 4px;
            font-weight: 600;
            transition: all 0.4s ease-in-out;
        }
 
        header h1:hover {
            transform: scale(1.1);
            text-shadow: 0 0 20px rgba(0, 255, 255, 1);
        }
 
        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.9);
            padding: 15px 30px;
        }
 
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            font-size: 1rem;
            transition: 0.3s;
            position: relative;
        }
 
        nav a:hover, nav a.active {
            color: #00e6e6;
        }
 
        nav a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #00e6e6;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: rgba(34, 34, 34, 0.8);
            border-radius: 10px;
            box-shadow: 0 12px 15px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
 
        .user-info {
            padding: 25px;
            background-color: rgba(0, 0, 0, 0.75);
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.6);
            border-left: 6px solid #00bcd4;
        }
 
        .user-info h2 {
            font-size: 2rem;
            color: #00e6e6;
            margin-bottom: 20px;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.7);
            font-weight: 600;
        }
 
        .user-info p {
            font-size: 1.1rem;
            margin: 10px 0;
            color: #ddd;
        }
 
        .btn-logout {
            display: inline-block;
            background-color: #ff4d4d;
            color: #fff;
            padding: 12px 22px;
            border-radius: 5px;
            font-size: 1.1rem;
            margin-top: 25px;
            transition: background-color 0.3s;
        }
 
        .btn-logout:hover {
            background-color: #e63946;
            cursor: pointer;
        }
 
        /* Notifications */
        .notifications {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 20px;
            margin-top: 35px;
            border-radius: 8px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.4);
        }
 
        .notifications h3 {
            font-size: 1.8rem;
            color: #00e6e6;
            margin-bottom: 20px;
            font-weight: 600;
        }
 
        .notification-item {
            padding: 10px;
            background-color: #333;
            margin: 12px 0;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
 
        /* Footer */
        footer {
            background-color: rgba(0, 0, 0, 0.9);
            text-align: center;
            padding: 12px 0;
            color: #fff;
            font-size: 0.9rem;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
 
        /* Animations */
        @keyframes backgroundEffect {
            0% { background: linear-gradient(135deg, #2d2d72, #00bcd4); }
            50% { background: linear-gradient(135deg, #111, #00e6e6); }
            100% { background: linear-gradient(135deg, #2d2d72, #00bcd4); }
        }
 
        /* Responsive Design */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                padding: 20px 0;
            }
 
            nav a {
                margin: 10px 0;
                font-size: 1.2rem;
            }
 
            .container {
                margin: 20px;
                padding: 15px;
            }
 
            .user-info {
                padding: 18px;
            }
 
            footer {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue sur votre espace utilisateur !</h1>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="utilisateur.html" class="active"><i class="fas fa-home"></i> Accueil</a>
        <a href="settingsUser.php"><i class="fas fa-cogs"></i> Gérer mon compte</a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </nav>
 
    <!-- Main Content -->
    <div class="container">
        <!-- Informations de l'utilisateur -->
          <?php
        $requete =$pdo -> query("SELECT login, email, role FROM utilisateurs");
        $utilisateurs = $requete ->fetchAll(PDO::FETCH_ASSOC);
        ?>

<div class="user-info">
<?php
if (isset($_SESSION['user'])) {
    $utilisateur = $_SESSION['user'];
?>
    <h2>Informations de l'utilisateur</h2>
    <p><strong>Nom d'utilisateur :</strong>
    <?php echo htmlspecialchars($_SESSION['user']['login'] ?? ''); ?>
</p>
<p><strong>Email :</strong>
    <?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>
</p>
<p><strong>Rôle :</strong>
    <?php echo htmlspecialchars($_SESSION['user']['role'] ?? ''); ?>
</p>
<?php
} else {
    echo "<p>Aucun utilisateur connecté.</p>";
}
?>
  </div>
         <!-- Notifications -->
         <div class="notifications">
             <h3>Notifications</h3>
             <div class="notification-item">
                 <p><strong>Info :</strong>  </p>
            </div>
             <div class="notification-item">
                 <p><strong>Compte crée le :</strong>  </p>
             </div>
             <div class="notification-item">
                <p><strong>Adresse mail de récuperation :</strong> <?= htmlspecialchars($utilisateur['email']) ?></p>
             </div>
         </div>
    </div>
 
    <!-- Footer -->
    <footer>
        <p>&copy; 2025 CyberPlatform. Tous droits réservés.</p>
    </footer>
</body>
</html>