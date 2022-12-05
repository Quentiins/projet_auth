<?php
// Initialise la session.
session_start();

// Vérifie si l'utilisateur est connecté.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Envoie sur la page login au bout de 24h (timeout).
if(isset($_SESSION["user"]))
{
    if(time()-$_SESSION["login_time_stamp"] > 864000) 
    {
        session_unset();
        session_destroy();
        header("Location: login.php");
    }
}
else
{
    header("Location: login.php");
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- Profil -->
        <div class="imga">
            <img src="images/user_profile.webp" class="img" alt="user">
            <a class="btnlogout" href="logout.php" name="logout_btn">Se déconnecter</a>
        </div>
    <h2 class="welcome">Vous êtes connecté sur le compte utilisateur <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h2>
    </div>
</body>
</html>