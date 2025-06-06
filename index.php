<?php session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page De Connexion</title>
    <style>
        /* Global styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }
 
        /* Dynamic background with blue tones */
        body {
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-size: 16px;
            background: linear-gradient(135deg, #2a2fbb, #00b3e6);
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
            transition: background 1s ease;
            overflow: hidden;
        }
 
        @keyframes gradientBG {
            0% { background: linear-gradient(135deg, #2a2fbb, #00b3e6); }
            25% { background: linear-gradient(135deg, #1f72b8, #76c7f7); }
            50% { background: linear-gradient(135deg, #0288d1, #4fa3db); }
            75% { background: linear-gradient(135deg, #003f6c, #38799d); }
            100% { background: linear-gradient(135deg, #2a2fbb, #00b3e6); }
        }
 
        .container {
            background: rgba(0, 0, 0, 0.8);
            border-radius: 15px;
            padding: 40px;
            width: 350px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3), 0 10px 20px rgba(0, 0, 255, 0.1);
            text-align: center;
            animation: slideIn 1s ease-out;
            position: relative;
        }
 
        @keyframes slideIn {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(0); }
        }
 
        .login-form h2 {
            margin-bottom: 20px;
            font-size: 28px;
            color: #00b3e6;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0px 0px 5px rgba(255, 255, 255, 0.5);
        }
 
        .input-group {
            margin-bottom: 15px;
            text-align: left;
            position: relative;
        }
 
        .input-group label {
            font-size: 14px;
            color: #aaa;
            transition: all 0.3s ease;
        }
 
        .input-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #444;
            border-radius: 5px;
            background: #222;
            color: #00b3e6;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 179, 230, 0.5);
        }
 
        .input-group input:focus {
            border-color: #00b3e6;
            box-shadow: 0 0 15px rgba(0, 179, 230, 0.8);
        }
 
        .input-group input:focus + label {
            transform: translateY(-20px);
            font-size: 12px;
            color: #00b3e6;
        }
 
        .btn {
            background-color: #00b3e6;
            color: #121212;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s ease;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 179, 230, 0.5);
        }
 
        .btn:hover {
            background-color: #0099cc;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 179, 230, 0.8);
        }
 
        p a {
            color: #00b3e6;
            text-decoration: none;
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
            transition: color 0.3s ease;
        }
 
        p a:hover {
            color: #0099cc;
        }
 
        .error-message {
            color: #ff4444;
            font-size: 14px;
            margin-top: 10px;
            display: none;
            display: block;
        }
 
        /* Loader animation */
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #00b3e6;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
            margin-top: 20px;
            display: none;
        }
 
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
 
        /* Particles effect for more dynamic feel */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://cdn.pixabay.com/photo/2016/11/29/10/05/abstract-1869640_960_720.jpg') repeat center center;
            z-index: -1;
            animation: particlesAnimation 10s infinite linear;
        }
 
        @keyframes particlesAnimation {
            0% { background-position: 0 0; }
            100% { background-position: 100% 100%; }
        }
    </style>
</head>

<body>
    <div class="particles"></div> <!-- Particles effect -->
    <div id="loginForm" class="container">
    <div class="login-form">
        <h2>Connexion</h2>
            <form action="login.php" method="POST">
    <div class="input-group">
        <input type="text" id="login" name="login" required>
        <label for="login">Nom d'utilisateur</label>
    </div>
    <div class="input-group">
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
        <label for="mot_de_passe">Mot de passe</label>
    </div>
    <div class="loader" id="loader"></div>
    <button type="submit" class="btn">Se connecter</button>
</form>
<?php
// Juste après le formulaire
if (isset($_SESSION['login_error'])) {
    echo '<p class="error-message">' . $_SESSION['login_error'] . '</p>';
    unset($_SESSION['login_error']);
}
?>

            <p><a href="passwrdForm.php">Mot de passe oublié ?</a></p>
            <p>Pas encore de compte ? <a href="register_form.php">Inscrivez-vous</a></p>
        </div>
    </div>
 
    <script>
    const form = document.getElementById("loginForm");
    const loader = document.getElementById("loader");

    form.addEventListener("submit", function () {
        loader.style.display = "block"; // Affiche le loader dès l'envoi du formulaire
    });
</script>

</body>
</html>
