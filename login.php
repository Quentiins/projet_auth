<?php
// Initialise la session.
session_start();
 
// Vérifie si l'utilisateur est connecté.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Inclut le fichier config.
require_once "config.php";
 
// Définit les variables et les initialises avec des valeurs vides.
$username = $password = "";
$user_type = "user";
$username_err = $password_err = $login_err = "";

// Les variables de session sont créées.
$_SESSION["user"] = $username;  
 
// Le temps de connexion est stocké dans une variable de session.
$_SESSION["login_time_stamp"] = time(); 

// Formulaire envoyé.
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Vérifie si le username est vide.
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer votre username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Vérifie si le mot de passe est vide.
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer votre mot de passe.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validation des informations.
    if(empty($username_err) && empty($password_err)){
        // Prépare un SELECT.
        $sql = "SELECT id, username, password, user_type FROM accounts WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Paramètre des variables.
            $stmt->bind_param("s", $param_username);
            
            $param_username = $username;
            
            if($stmt->execute()){
                // Stock le résultat.
                $stmt->store_result();
                
                // Vérifie si l'utilisateur existe pour ensuite vérifier le mot de passe.
                if($stmt->num_rows == 1){                    
                    // Paramètre les variables de résultat.
                    $stmt->bind_result($id, $username, $hashed_password, $user_type);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            // Mot de passe correct, démarre une session.
                            session_start();
                            
                            // Stock les données dans des variables de session.
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["user_type"] = $user_type;
                            
                            // Vérifie le type de l'utilisateur et redirige celui-ci selon son rôle.
                            if($user_type == "user"){ 
                                header("location: welcome.php");
                            }else{
                                header("Location: welcomeAdmin.php"); 
                                }
                            }                            
                        } else{
                            // Le mot de passe n'est pas valide.
                            $login_err = "Username ou mot de passe incorrect.";
                        }
                } else{
                    // Le username n'existe pas.
                    $login_err = "Username ou mot de passe incorrect.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Ferme la requête.
            $stmt->close();
        }
    }
    
    // Ferme la connexion.
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Se connecter</h2>
        
        <!-- Erreur de connection -->
        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <!-- Formulaire de connection -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
            <button type="submit" class="btnlog" name="login_btn">Se connecter</button>
            </div>
            <p>Pas encore de compte ? <a href="register.php">S'inscrire</a>.</p>
        </form>
    </div>
</body>
</html>