<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "../config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM accounts WHERE id = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
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
                // URL doesn't contain valid id parameter. Redirect to error page
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="wrapperread">
        <div>
            <div>
                <div>
                    <h1 class="titleread">Information de <?php echo $row["username"]; ?> </h1>
                    <div class="form-groupread">
                        <label>Username</label>
                        <p><?php echo $row["username"]; ?></p>
                    </div>
                    <div class="form-groupread">
                        <label>Email</label>
                        <p><?php echo $row["email"]; ?></p>
                    </div>
                    <div class="form-groupread">
                        <label>Rôle</label>
                        <p><?php echo $row["user_type"]; ?></p>
                    </div>
                    <a href="../welcomeAdmin.php" class="btnread">Retour</a>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>