<?php 
//Eric Rubio Sanchez
require_once("../Model/BDD.php");
require_once("../Controlador/session.php");

/**
 * Summary of validarDades
 *      Aqui comprovem que les dades introduides siguin correctes.
 * @param String $nom nom de conte
 * @param String $correu el correu del usuari.
 * @param String $password la contrasenya del usuari.
 * @param String $password2 la contrasenya que l'usuari ha de tornar a introduir per comrpbar que no s'hagi equivocat.
 * @return String retorna un string de errors separats per <br>
 */
function validarDades($password,$password2,){
    $errors="";
    if(empty($password)){
        $errors.="Contrasenya buit <br>";
    }
    if($password!=$password2){
        $errors.="Les contrasenyes han de ser iguals <br>";
    }
    if(empty($password2)){
        $errors.="Has de que tornar a intorduir la contrasenya <br>";
    }
    return $errors;
/*action="<?php echo $_SERVER["PHP_SELF"];?>"id= "form"*/    
}




if ($_SERVER["REQUEST_METHOD"]=="POST"){
    //Agafem les variables del formulari i les enviem a una funcio del controlador en la que tartem d'evitar l'injeccio de codi.
    $password = tractarDades($_POST["password"]);
    $password2 = tractarDades($_POST["password2"]);

    //Crida la funcio validarDades on em retorna un string amb tots els erros de les validacions.
    $errors=validarDades($password,$password2);
    $correcte="";
    session_start();
    if(isset($_SESSION["correu"])){
        $correu=$_SESSION["correu"];
        $token=$_SESSION["token"];
        if(!comprovarToken($token,$correu)){
        $errors.="El token es incorrecte. <br>";
        }
    }

    if($errors==""){
        $correcte="Totes les dades son correctes <br>";
       
        try{
            if(!isset($correu)){
                $correu=$_SESSION['newsession'];
            }
            canviarContrasenya($correu,password_hash($password,PASSWORD_BCRYPT));
            $correcte.="Contrasenya canviada";
            
            
        }
        catch(Exception $e){
            $errors.= "Contrasenya incorrecte.<br>";
        }
    }
    //Un include perque carregui tot l'HTML desde aqui per tenir les variables i el HTML en el mateix lloc.
}

require_once("../Vista/canviarContrasenya.vista.php");
?>