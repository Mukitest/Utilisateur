<?php 
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'authpage';
$user = 'root'; // Modifier si nécessaire
$pass = '';    // Modifier si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

//Traiteement de la modification du mot de passe (depuis une session connecté)
if (isset($_POST['updatePwd'])) {
    if (!isset($_SESSION['user_id'])) {
        die("Utilisateur non connecté.");
    }
  //MODIFICATION DU MOT DE PASSE 
    $user_id = $_SESSION['user_id']; // Stocké au login
    $currentPwd = $_POST['currentPwd'] ?? '';
    $newPwd = $_POST['newPwd'] ?? '';
    $confirmPwd = $_POST['confirmPwd'] ?? '';

    if (empty($currentPwd) || empty($newPwd) || empty($confirmPwd)) {
        echo "<script>alert('Veuillez remplir tout les champs.');</script>";
    } elseif ($newPwd !== $confirmPwd) {
        echo "<script>alert('Les nouveaux mots de passe ne correspondent pas.');</script>";
    } else {
        // Vérifier le mot de passe actuel
        $stmt = $pdo->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($currentPwd, $user['mot_de_passe'])) {
            // Mettre à jour le mot de passe
            $hashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
            $update->execute([$hashedPwd, $user_id]);
            echo "<script>alert('Mot de passe mis à jour avec succès.');</script>";
        } else {
            echo "<script>alert('Mot de passe actuel incorrect.');</script>";
        }
    }
  }
// MODIFICATION DE L'ADRESSE MAIL DE RECUPERATION
    if (isset($_POST['updateEmail'])) {
    if (!isset($_SESSION['user_id'])) {
        die("Utilisateur non connecté.");
    }

    $user_id = $_SESSION['user_id'];
    $newEmail = filter_var($_POST['emailRecovery'], FILTER_VALIDATE_EMAIL);

    if (!$newEmail) {
        echo "<script>alert('Email invalide.');</script>";
    } else {
        $updateEmail = $pdo->prepare("UPDATE utilisateurs SET email = ? WHERE id = ?");
        $updateEmail->execute([$newEmail, $user_id]);
        echo "<script>alert('Email mis à jour avec succès.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paramètres - CyberPanel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <style>
    :root {
      --primary-color: #00ffff;
      --bg-color: #0f0f0f;
      --text-color: #ffffff;
    }
 
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--bg-color);
      color: var(--text-color);
      transition: background 0.3s, color 0.3s;
    }
 
    header {
      padding: 20px;
      background: rgba(0,0,0,0.85);
      text-align: center;
      border-bottom: 2px solid var(--primary-color);
    }
 
    header h1 {
      font-family: 'Orbitron', sans-serif;
      font-size: 2rem;
      color: var(--primary-color);
      text-shadow: 0 0 8px var(--primary-color);
    }
 
    nav {
      display: flex;
      justify-content: space-around;
      background: rgba(0,0,0,0.9);
      padding: 12px 0;
    }
 
    nav a {
      color: var(--text-color);
      text-decoration: none;
      padding: 10px 16px;
    }
 
    nav a.active,
    nav a:hover {
      color: var(--primary-color);
    }
 
    .container {
      max-width: 900px;
      margin: 30px auto;
      background: rgba(0,0,0,0.6);
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
    }
 
    h2 {
      color: var(--primary-color);
      border-bottom: 1px solid var(--primary-color);
      padding-bottom: 5px;
      margin-bottom: 20px;
    }
 
    .section {
      margin-bottom: 40px;
    }
 
    .section label {
      display: block;
      margin: 10px 0 5px;
    }
 
    .section input[type="color"],
    .section input[type="text"],
    .section input[type="password"],
    .section input[type="email"],
    .section button {
      padding: 8px;
      margin: 5px 0 15px;
      border-radius: 5px;
      border: none;
      font-size: 1rem;
      width: 100%;
    }
 
    button {
      background-color: var(--primary-color);
      color: #000;
      cursor: pointer;
    }
 
    button:hover {
      opacity: 0.85;
    }
 
    footer {
      text-align: center;
      padding: 15px;
      background: rgba(0, 0, 0, 0.9);
      margin-top: 40px;
      font-size: 0.85rem;
    }
 
    input[type="checkbox"] {
      transform: scale(1.2);
      margin-right: 10px;
    }
  </style>
</head>
<body>
 
  <header>
    <h1>Gérer mon compte</h1>
  </header>
 
  <nav>
    <a href="utilisateur.php"><i class="fas fa-home"></i> Accueil</a>
    <a href="settingsUser.php" class="active"><i class="fas fa-cogs"></i> Gérer mon compte</a>
    <a href="index.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
  </nav>
 
  <div class="container">
 
    <!-- Apparence -->
    <div class="section">
      <h2>Apparence</h2>
      <label for="color">Couleur principale :</label>
      <input type="color" id="color" value="#00ffff" />
      <button onclick="resetColor()">Réinitialiser la couleur</button>
    </div>
 
    <!-- Modifier le mot de passe -->
     <div class="section">
<form method="POST" action="">
  <label for="currentPwd">Mot de passe actuel :</label>
  <input type="password" name="currentPwd" id="currentPwd" placeholder="Mot de passe actuel">

  <label for="newPwd">Nouveau mot de passe :</label>
  <input type="password" name="newPwd" id="newPwd" placeholder="Nouveau mot de passe">

  <label for="confirmPwd">Confirmer le mot de passe :</label>
  <input type="password" name="confirmPwd" id="confirmPwd" placeholder="Confirmer le mot de passe">

  <button type="submit" name="updatePwd" class="btn"> Mettre à jour le mot de passe</button>
</form>
</div>
    <!-- Email de récupération -->
    <div class="section">
      <h2>Email de récupération</h2>
      <form method="POST" action="modifier.php">
      <label for="emailRecovery">Modifier l'adresse de récuperation</label>
      <input type="email" id="emailRecovery" name="updateEmail" placeholder="Modifier mon adresse mail">
      <button>Mettre à jour l'email</button>
      </form>
    </div>

    <!-- Session -->
    <div class="section">
      <h2>Session</h2>
      <label><input type="checkbox" id="rememberSession"> Se souvenir de ma session</label>
    </div>
  </div>
 
  <footer>
    &copy; 2025 CyberPlatform Admin. Tous droits réservés.
  </footer>
 
  <script>
    function applyColor() {
      const color = localStorage.getItem('color') || '#00ffff';
      document.documentElement.style.setProperty('--primary-color', color);
      document.getElementById('color').value = color;
    }
 
    document.getElementById('color').addEventListener('input', function () {
      localStorage.setItem('color', this.value);
      applyColor();
    });
 
    function resetColor() {
      localStorage.removeItem('color');
      applyColor();
    }
 
    function changePassword() {
      const current = document.getElementById("currentPwd").value;
      const newPwd = document.getElementById("newPwd").value;
      const confirm = document.getElementById("confirmPwd").value;
 
      if (!current || !newPwd || !confirm) {
        alert("Tous les champs doivent être remplis.");
        return;
      }
      if (newPwd !== confirm) {
        alert("Les nouveaux mots de passe ne correspondent pas.");
        return;
      }
 
      // Ici tu peux faire une requête fetch/ajax pour envoyer les infos au serveur
      alert("Mot de passe mis à jour (simulé).");
    }
 
    applyColor();
  </script>
 
</body>
</html>