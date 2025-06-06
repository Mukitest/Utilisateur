<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
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
        <h2>Inscription</h2>
        <form action="register.php" method="POST">
            <input type="text" name="login" placeholder="Nom d'utilisateur" required>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
            <input type="email" name="email" placeholder="Email" required>
            <select name="role" readonly>
                <option value="utilisateur" selected>Utilisateur</option>
            </select>
            <button type="submit" class="btn">S'inscrire</button>
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
