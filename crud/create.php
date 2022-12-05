<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

// Validation username.
if(empty(trim($_POST["username"]))){
    $username_err = "Veuillez saisir un username.";
} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
    $username_err = "Le username ne peut contenir seulement des lettres, des nombres et des underscores.";
} else{
    // Prépare un SELECT.
    $sql = "SELECT id FROM accounts WHERE username = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Paramètre des variables.
        $stmt->bind_param("s", $param_username);
        
        $param_username = trim($_POST["username"]);
        
        if($stmt->execute()){
            // Stock le résultat.
            $stmt->store_result();
            
            if($stmt->num_rows == 1){
                $username_err = "Le username est déjà utilisé.";
            } else{
                $username = trim($_POST["username"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Ferme la requête.
        $stmt->close();
    }
}
    
// Validation email.
if(empty(trim($_POST["email"]))){
    $email_err = "Veuillez saisir un email.";
} else{
    // Prépare un SELECT.
    $sql = "SELECT id FROM accounts WHERE email = ?";
            
    if($stmt = $mysqli->prepare($sql)){
        // Paramètre des variables.
        $stmt->bind_param("s", $param_email);
                
        $param_email = trim($_POST["email"]);
                
        if($stmt->execute()){
            // Stock le résultat.
            $stmt->store_result();
                    
            if($stmt->num_rows == 1){
                $email_err = "L'adresse mail est déjà utilisée.";
            } else{
                $email = trim($_POST["email"]);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    
        // Ferme la requête.
        $stmt->close();
    }
}
    
// Validation mot de passe.
if(empty(trim($_POST["password"]))){
    $password_err = "Veuillez saisir un mot de passe.";     
} elseif(strlen(trim($_POST["password"])) < 6){
    $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
} else{
    $password = trim($_POST["password"]);
}

// Validation de confirmation de mot de passe.
if(empty(trim($_POST["confirm_password"]))){
    $confirm_password_err = "Veuillez confirmer votre mot de passe.";     
} else{
    $confirm_password = trim($_POST["confirm_password"]);
    if(empty($password_err) && ($password != $confirm_password)){
        $confirm_password_err = "Les mots de passe sont différents.";
    }
}
    
// Vérifie les erreurs des inputs avant de les insérer dans la base de donnée.
if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
    // Prépare un INSERT.
    $sql = "INSERT INTO accounts (username, email, password) VALUES (?, ?, ?)";

    if($stmt = $mysqli->prepare($sql)){
        // Paramètres des variables.
        $stmt->bind_param("sss", $param_username, $param_email, $param_password);
            
        $param_username = $username;
        $param_email = $email;
        $param_password = password_hash($password, PASSWORD_BCRYPT); // Crypte le mot de passe.
            
        if($stmt->execute()){
            // Redirige vers la page login.
            header("location: ../welcomeAdmin.php");
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
    <title>Create User</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="wrappercreate">
                    <h2>Création d'un utilisateur</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                        </div>
                        <button type="submit" class="btn" name="register_btn">Inscrire</button>
                        <a href="../welcomeAdmin.php" class="btnback">Annuler</a>
                    </form>
    </div>
</body>
</html>