<?php
//Eric Rubio Sanchez


require_once("../Model/BDD.php");
require_once("../Controlador/session.php");

if ($_SERVER["REQUEST_METHOD"]=="GET"&& isset($_GET["correu"])){
    $correu=$_GET["correu"];
    $nom=$_GET["nom"];
    $social=$_GET["social"];
}else{
    echo("Error amb les credencials");
    header("Location: ../Vista/index.vista.php");
    exit();
}

try{$nom=existeixUsuari($correu);
    try{
        iniciarSession($correu,$nom);
    }
    catch(Exception $e){
    }
}catch(Exception $e){
    crearUsuariOauth($correu,$nom,$social);
    iniciarSession($correu,$nom);
}





?>