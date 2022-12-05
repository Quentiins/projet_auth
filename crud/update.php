<?php
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$username = $email = "";
$username_err = $email_err = $user_type_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validation username.
    $input_username = trim($_POST["username"]);
    if(empty($input_username)){
        $username_err = "Veuillez saisir un username.";
    } elseif(!filter_var($input_username, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>'/^[a-zA-Z0-9_]+$/')))){
        $username_err = "Veuillez saisir un username valide.";
    } else{
        $username = $input_username;
    }
    
    // Validation email.
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Veuillez saisir une adresse mail.";     
    } else{
        $email = $input_email;
    }

        // Validation rôle.
        $input_user_type = trim($_POST["user_type"]);
        if(empty($input_user_type)){
            $user_type_err = "Veuillez saisir un rôle.";     
        } else{
            $user_type = $input_user_type;
        }
    
    // Vérifie les erreurs des inputs avant de les insérer dans la base de donnée.
    if(empty($username_err) && empty($email_err) && empty($user_type_err)){
        
        // Prépare un INSERT.
        $sql = "UPDATE accounts SET username=?, email=?, user_type=? WHERE id=?";

        if($stmt = $mysqli->prepare($sql)){
            // Paramètres des variables.
            $stmt->bind_param("sssi", $param_username, $param_email, $param_user_type, $param_id);
            
            $param_username = $username;
            $param_email = $email;
            $param_user_type = $user_type;
            $param_id = $id;
            
            if($stmt->execute()){
                // Redirige vers la page login.
                header("location: ../welcomeAdmin.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Ferme la requête.
            $stmt->close();
        }
    }
    
    // Ferme la connexion.
    $mysqli->close();
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM accounts WHERE id = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $username = $row["username"];
                    $email = $row["email"];
                    $user_type = $row["user_type"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
        
        // Close connection
        $mysqli->close();
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modification</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="wrapper">
                    <h2>Modifier l'utilisateur</h2>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                            <label>Rôle</label>
                            <select name="user_type" class="user-select">
                                <option value="user">user</option>
                                <option value="admin">admin</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $user_type_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn" value="Valider">
                        <a href="../welcomeAdmin.php" class="btnupdate">Annuler</a>
                    </form>
    </div>
</body>
</html>