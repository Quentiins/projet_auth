<?php
// Information de la base de donnée utilisée.
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'projetauth');
 
// Connexion à la base de donnée MYSQL.
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Vérifie la connexion.
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>