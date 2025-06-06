<?php session_start(); 
// Connexion à la base de données
$host = 'localhost';
$dbname = 'authpage';
$user = 'root';
$pass = '';

// Fonction qui permet de se connecter a la base de données 
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
    <title>Admin Panel - Cyberpunk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Fonts et thème */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #111, #0ff);
            color: #fff;
            animation: backgroundEffect 15s infinite ease-in-out;
        }
 
        header {
            background-color: rgba(0, 0, 0, 0.85);
            text-align: center;
            padding: 30px 20px;
            border-bottom: 2px solid #00e6e6;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }
 
        header h1 {
            font-size: 2.8rem;
            color: #00e6e6;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 10px #00ffff;
        }
 
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
 
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 255, 255, 0.3);
        }
 
        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #00ffff;
        }
 
        .admin-actions {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
 
        .admin-actions a {
            background: #00bcd4;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin: 5px;
            transition: 0.3s;
        }
 
        .admin-actions a:hover {
            background: #0097a7;
        }
 
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
 
        th, td {
            padding: 12px;
            border-bottom: 1px solid #444;
            text-align: left;
        }
 
        th {
            color: #00e6e6;
            background-color: rgba(0, 0, 0, 0.6);
        }
 
        .actions button {
            background-color: #1e90ff;
            border: none;
            color: #fff;
            padding: 6px 12px;
            margin: 0 5px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }
 
        .actions .delete {
            background-color: #e63946;
        }
 
        .actions button:hover {
            opacity: 0.8;
        }
 
        footer {
            text-align: center;
            padding: 10px 0;
            background: rgba(0, 0, 0, 0.9);
            color: #ccc;
            font-size: 0.8rem;
            margin-top: 40px;
        }
 
        @keyframes backgroundEffect {
            0% { background: linear-gradient(135deg, #111, #0ff); }
            50% { background: linear-gradient(135deg, #222, #00e6e6); }
            100% { background: linear-gradient(135deg, #111, #0ff); }
        }
 
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: center;
            }
 
            .admin-actions {
                flex-direction: column;
                align-items: flex-start;
            }
 
            .admin-actions a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>Panneau d'Administration</h1>
    </header>
 
    <nav>
    <!-- NavBar  -->
        <a href="adminPanel.php" class="active"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
        <a href="#"><i class="fas fa-file-alt"></i> Logs système</a>
        <a href="settings.php"><i class="fas fa-cogs"></i> Paramètres</a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </nav>
 
    <div class="container">
        <h2>Liste des Utilisateurs</h2>

 <!-- Boutons qui permette de creer des éléments -->
        <div class="admin-actions">
            <a href="create_users.php"><i class="fas fa-user-plus"></i> Creer un utilisateur</a>
            <a href="create_admin.php"><i class="fas fa-user-plus"></i> Creer un admin</a>
            <a href="#"><i class="fas fa-database"></i> Exporter la base</a>
        </div>

        <!--Fonction qui permet de récupérer les infos de la base -->
        <?php
        $requete =$pdo -> query("SELECT * FROM utilisateurs");
        $utilisateurs = $requete ->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <!-- Création d'un tableau qui affichera les éléements voulue  -->
        <table>
            <thead>
                <!-- Boucle qui listera les éléments rechercher depuis la base -->
                <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <!-- Affichage des variables sous formes de ligne dans le tableau  -->
                    <td><?= htmlspecialchars($utilisateur['id']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['login']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                    <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                    <td class="actions">
                        <a href="modifier.php?id=<?= $utilisateur['id'] ?>" class="btn btn-sm btn-warning">
    <i class="fas fa-edit"></i> Modifier
</a>
                        <a href="supprimer.php?id=<?= $utilisateur['id'] ?>" 
   class="btn btn-danger btn-sm"
   onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
   <i class="fas fa-trash"></i> Supprimer
</a>            
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
 
    <footer>
        <p>&copy; 2025 CyberPlatform Admin. Tous droits réservés.</p>
    </footer>
</body>
</html>