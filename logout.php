<?php
// Initialise la session.
session_start();
 
// Désactive toutes les variables de session.
$_SESSION = array();
 
// Détruit la session.
session_destroy();
 
// Redirige vers la page login.
header("location: login.php");
exit;
?>