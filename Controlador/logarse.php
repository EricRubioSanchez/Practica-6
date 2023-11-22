<?php 
//Eric Rubio Sanchez
require_once("../Model/BDD.php");
require_once("../Controlador/session.php");


/**
 * Summary of validarDades
 *      Aqui comprovem que les dades introduides siguin correctes.
 * @param String $correu el correu del usuari.
 * @param String $password la contrasenya del usuari.
 * @return String retorna un string de errors separats per <br>
 */
function validarDades($correu,$password){
    $errors="";
    if(empty($correu)){
        $errors.="Correu buit <br>";
    }elseif (!filter_var($correu, FILTER_VALIDATE_EMAIL)) {
        $errors.= "Correu erroni <br>";
      }
    if(empty($password)){
        $errors.="Contrasenya buit <br>";
    }
    return $errors;
/*action="<?php echo $_SERVER["PHP_SELF"];?>"id= "form"*/    
}

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    //Agafem les variables del formulari i les enviem a una funcio del controlador en la que tartem d'evitar l'injeccio de codi.
    $errors="";
    $correu = tractarDades($_POST["correu"]);
    $password = tractarDades($_POST["password"]);
    session_start();

    /*
    define('GOOGLE_CLIENT_ID','287632858042-td5pnbaha5lmt20i0ruede803qk973c8.apps.googleusercontent.com');
    define('GOOGLE_CLIENT_SECRET','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');
    define('GOOGLE_REDIRECT_URL','http://localhost/UF1/Practica%205/');
    $google_client = new Google_Client();
    $google_client->setClientId(GOOGLE_CLIENT_ID);
    $google_client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $google_client->setRedirectUri(GOOGLE_REDIRECT_URL);
    $google_client->addScope('email');
    $google_client->addScope('profile');
    */

    if(isset($_SESSION["contrasenyaErronea"])){$contrasenyaErronea=$_SESSION["contrasenyaErronea"];}
    if (isset($contrasenyaErronea) && $contrasenyaErronea>=3){
        $ip = $_SERVER['REMOTE_ADDR'];
        $captcha = $_POST['g-recaptcha-response'];
        $secretkey = "6LdnHfAoAAAAABtPplKSzUwnQSGNlI6YJWOSzfTt";
        $respuesta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$captcha&remoteip=$ip");
        $atributos = json_decode($respuesta,TRUE);
        if(!$atributos['success']){
            $errors.="Verificar captcha <br>";
    }
    }
    

    //Crida la funcio validarDades on em retorna un string amb tots els erros de les validacions.
    $errors.=validarDades($correu,$password);
    $correcte="";

    if($errors==""){
        $correcte="Totes les dades son correctes <br>";
        try{$nom=existeixUsuari($correu);
            $correcte.="Usuari trobat a la base de dades.";
            try{
                if(comprovarContrasenya($correu,$password)){
                    iniciarSession($correu,$nom);
            }else{
                if(!isset($_SESSION["contrasenyaErronea"])){$_SESSION["contrasenyaErronea"]=0;}
                $contrasenyaErronea=$_SESSION["contrasenyaErronea"]+1;
                $_SESSION["contrasenyaErronea"]=$contrasenyaErronea;
                $errors.= "Contrasenya incorrecte.<br>";}
            }
            catch(Exception $e){
            }
        }catch(Exception $e){
            $errors.= "L'Usuari no existeix a la base de dades.<br>";
        }
        
        

    }
    //Un include perque carregui tot l'HTML desde aqui per tenir les variables i el HTML en el mateix lloc.
}

require_once("../Vista/logarse.vista.php");
?>