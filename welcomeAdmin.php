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
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="imga">
            <img src="images/admin_profile.webp" class="img" alt="user">
            <a class="btnlogout" href="logout.php" name="logout_btn">Se déconnecter</a>
        </div>
    <h2 class="welcome">Vous êtes connecté sur le compte administrateur <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h2>
    </div>
    <div class="wrappertab">
                    <div>
                        <h2>Liste des utilisateurs</h2>
                        <a href="crud/create.php" class="btntab"> + Ajouter un utilisateur</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM accounts";
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
                            echo '<table>';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Username</th>";
                                        echo "<th>Email</th>";
                                        echo "<th>Rôle</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = $result->fetch_array()){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['username'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['user_type'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="crud/read.php?id='. $row['id'] .'" class="read" title="Voir utilisateur" data-toggle="tooltip"><span>Voir</span></a>';
                                            echo '<a href="crud/update.php?id='. $row['id'] .'" class="update" title="Modifier utilisateur" data-toggle="tooltip"><span >Modifier</span></a>';
                                            echo '<a href="crud/delete.php?id='. $row['id'] .'" title="Supprimer utilisateur" data-toggle="tooltip"><span>Supprimer</span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            $result->free();
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    
                    // Close connection
                    $mysqli->close();
                    ?>
    </div>
</body>
</html>