<?php 
//Eric Rubio Sanchez
require_once("../Model/BDD.php");
require_once("../Controlador/session.php");

/**
 * Summary of validarDades
 *      Aqui comprovem que les dades introduides siguin correctes.
 * @param String $idArticle id del article.
 * @return String retorna un string de errors separats per <br>
 */
function validarDades($idArticle){
    $errors="";
    if(empty($idArticle)){
        $errors.="Id del article buit <br>";
    }
    return $errors;
/*action="<?php echo $_SERVER["PHP_SELF"];?>"id= "form"*/    
}


if ($_SERVER["REQUEST_METHOD"]=="POST"){
    //Agafem les variables del formulari i les enviem a una funcio del controlador en la que tartem d'evitar l'injeccio de codi.
    $idArticle = tractarDades($_POST["id_article"]);

    //Crida la funcio validarDades on em retorna un string amb tots els erros de les validacions.
    $errors=validarDades($idArticle);
    $correcte="";

    if($errors==""){
        $correcte="Totes les dades son correctes";
        if(autentificacioArticleUsuari($idArticle)){
            try{esborrarArticle($idArticle);
                $correcte.="<br> Article Esborrat";}
            catch(PDOException $e){
                $errors.="Error amb la conexió a la base de dades.";
            }
        }else{
            $errors.="No es pot accedir al article.";
        }
        

    }
    //Un include perque carregui tot l'HTML desde aqui per tenir les variables i el HTML en el mateix lloc.
}

require_once("../Vista/esborrar.vista.php");

?>