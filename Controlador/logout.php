<?php
//Eric Rubio Sanchez
//Desloga i retorna a la pagina de articles.
session_start();
unset($_SESSION["newsession"]);
header("Location: ../Vista/index.vista.php");
exit();
?>