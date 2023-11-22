<?php 
//Eric Rubio Sanchez
require_once("../controladorMailerPHP.php");
require_once("../Model/BDD.php");
require_once("../Controlador/session.php");


/**
 * Summary of validarDades
 *      Aqui comprovem que les dades introduides siguin correctes.
 * @param String $correu el correu del usuari.
 * @param String $password la contrasenya del usuari.
 * @return String retorna un string de errors separats per <br>
 */
function validarDades($correu){
    $errors="";
    if(empty($correu)){
        $errors.="Correu buit <br>";
    }elseif (!filter_var($correu, FILTER_VALIDATE_EMAIL)) {
        $errors.= "Correu erroni <br>";
      }
    return $errors;
/*action="<?php echo $_SERVER["PHP_SELF"];?>"id= "form"*/    
}



if ($_SERVER["REQUEST_METHOD"]=="POST"){
    //Agafem les variables del formulari i les enviem a una funcio del controlador en la que tartem d'evitar l'injeccio de codi.

    $correu = tractarDades($_POST["correu"]);

    //Crida la funcio validarDades on em retorna un string amb tots els erros de les validacions.
    $errors=validarDades($correu);
    $correcte="";

    if($errors==""){
        $correcte="Totes les dades son correctes <br>";
        try{$nom=existeixUsuari($correu);
            $correcte.="Usuari trobat a la base de dades.<br>";
            try{
                //No es pot desencriptar la contrasenya guardada a la base de dades pel tipus d'encriptació
                //Així que li assigno 1234 de contrasenya i que quan entri la canviï.
                $token = generaTokenPass($correu); 
                $url='http://'.$_SERVER["SERVER_NAME"].'/UF1/Practica%205/Vista/canviarContrasenya.vista.php?correu='.$correu.'&token='.$token;
                $cuerpo="Bones <br/><br/> Heu solicitat un reinici de la contrasenya.<br/><br/>Per a restaurar la contrsenya visita la següent direcció: <a href='$url'>$url</a><br/><br/>Aquest enllaç caducarà a les 2 hores";
                enviarcorreuPHPMailer($correu,$cuerpo);
                $correcte.="Correu Enviat";
            }
            catch(Exception $e){
                $errors.= "Error amb la conexió.<br>";
            }
        }catch(Exception $e){
            $errors.= "L'Usuari no existeix a la base de dades.<br>";
        }
        
        

    }
    //Un include perque carregui tot l'HTML desde aqui per tenir les variables i el HTML en el mateix lloc.
}

require_once("../Vista/enviarCorreu.vista.php");
?>