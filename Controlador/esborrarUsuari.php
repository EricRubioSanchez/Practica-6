<?php 
//Eric Rubio Sanchez
require_once '../Model/BDD.php';

session_start();
$correu = $_SESSION['newsession'];


esborrarUsuari($correu);
header("Location: ../Controlador/logout.php");
exit();

?>