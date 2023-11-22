<?php 
//Eric Rubio Sanchez
require_once("env.php");

/**
 * Summary of obrirBDD
 *    Retorna un PDO si s'ha pogut conectar al servidor o un null si no ha pogut conectarse.
 * @return PDO|null
 */	
function obrirBDD() : PDO{
    try {
      // Ens connectem a la base de dades
      //crearem nou objecte PDO (connexió,base_de_dades,usuari,password);
      $connexio = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
      return $connexio;
    } catch(PDOException $e){ //
  
  
      return null;
    }   
    
}

/**
 * Summary of executarSentencia
 *      En aquesta funcio executem les sentencies SQL.
 * @param string $sentencia Sentencia MySQL
 * @param array $array      Array que porta les variables
 * @param PDO $connexio     Conexio a la BDD
 * @return array|false retorna una array per els SELECTS i un false per la resta.
 */
function executarSentencia($sentencia,$array,$connexio){

    // Preparem la consulta SQL
    $statement = $connexio->prepare($sentencia);
  
    // Executem la consulta
    $statement->execute($array);
  
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  
    return $result;
  
}

/**
* Summary of tancarBDD
* @param PDO $connexio
* @return null
*/
function tancarBDD($connexio){
    $connexio=null;
    return $connexio;
    }

/**
 * Summary of crearUsuari
 *  Aquesta funcio serveix per crear l'usuari a la base de dades amb la password ja encripatada d'abans.
 * @param String $nom 
 * @param String $correu
 * @param String $password encriptada en enregistrarse.php
 * @return void
 */
function crearUsuari($nom,$correu,$password){
  $conexio=obrirBDD();
    if(!is_null($conexio)){
      $setencia = "INSERT INTO usuaris (nom, correu, contrasenya) VALUES (:nom, :correu, :password)";
      $array=array(':nom' => $nom, ':correu' => $correu, ':password' => $password);

      $result=executarSentencia($setencia,$array,$conexio);

      $conexio=tancarBDD($conexio);
    }
}

/**
 * Summary of generaTokenPass
 *  Genera i agrega un token a la base de dades amb una data d'expiració de 2 hores.
 * @param String $correu
 * @return string $token
 */
function generaTokenPass($correu){
  $conexio=obrirBDD();
    if(!is_null($conexio)){
      $token = bin2hex(openssl_random_pseudo_bytes(16));

      $setencia = "UPDATE usuaris  SET reset_token=:token, expires=NOW() + INTERVAL 2 HOUR WHERE correu=:correu";
      $array=array(':token' => $token,':correu' => $correu);

      executarSentencia($setencia,$array,$conexio);

      $conexio=tancarBDD($conexio);
      return $token;
    }
}
/**
 * Summary of comprovarToken
 *  comprova que el token de la pagina web sigui el mateix que el que esta guardat a la base de dades i que no estigui caducat.
 * @param String $correu
 * @param String $token
 * @return void
 */
function comprovarToken($token,$correu){
  $conexio=obrirBDD();
    if(!is_null($conexio)){
      $sentencia=("UPDATE usuaris  SET reset_token=null, expires=null WHERE expires< NOW();");
      $array=array();
      executarSentencia($sentencia,$array,$conexio);

      $setencia = "SELECT COUNT(*) FROM usuaris  WHERE correu=:correu AND reset_token=:token";
      $array=array(':correu' => $correu,':token' => $token);

      $result=executarSentencia($setencia,$array,$conexio);

      $conexio=tancarBDD($conexio);
      if($result[0]["COUNT(*)"]==1){return true;

      }else{return false;}
    }
}
/**
 * Summary of existeixUsuari
 *  si existeix l'usuari no fa res si no existeix retorna un exception
 * @param String $correu
 * @return void
 */
function existeixUsuari($correu){
$conexio=obrirBDD();
if(!is_null($conexio)){
  $setencia = "SELECT COUNT(*) FROM `usuaris` WHERE correu = :correu;";
  $array=array(':correu' => $correu);

  $result=executarSentencia($setencia,$array,$conexio);
  if(($result[0]['COUNT(*)'])==0){throw new Exception("L'usuari no existeix a la base de dades.");}
  
  $setencia = "SELECT nom FROM `usuaris` WHERE correu = :correu;";
  $array=array(':correu' => $correu);
  $result=executarSentencia($setencia,$array,$conexio);

  $conexio=tancarBDD($conexio);
  return $result[0]['nom'];
}
}


/**
 * Summary of comprovarContrasenya
 * comprovem si la contrasenya que han ficat en el login es la mateixa que la de la base de dades.
 * @param String $usuari el correu del usuari.
 * @param String $password la contrasenya sense encriptar.
 * @return void
 */
function comprovarContrasenya($usuari,$password){
  $conexio=obrirBDD();
  if(!is_null($conexio)){
    $setencia="SELECT contrasenya FROM `usuaris` WHERE correu=:correu;";
    $array=array(':correu' => $usuari);
  
    $result=executarSentencia($setencia,$array,$conexio);
    if(empty($result)){throw new Exception("La contrasenya no es la mateixa");}
    if(password_verify($password,$result[0]["contrasenya"])){return true;}
    $conexio=tancarBDD($conexio);
  }

}

/**
 * Summary of crearArticle
 *  Agafa la id en la primera sentencia i en la segona crea l'article.
 * @param String $article
 * @return void
 */
function crearArticle($article){
  $conexio=obrirBDD();
  session_start();
  $_SESSION['newsession'];
  if(!is_null($conexio)){
    $setencia = "SELECT id FROM usuaris WHERE correu=:correu";
    $array=array(':correu' => $_SESSION['newsession']);
    $result=executarSentencia($setencia,$array,$conexio);

    $setencia = "INSERT INTO articles (article, id_usuari) VALUES (:article, :id)";
    $array=array(':article' => $article, ':id' => $result[0]["id"]);

    $result=executarSentencia($setencia,$array,$conexio);
    $conexio=tancarBDD($conexio);
  }
}

/**
 * Summary of autentificacioArticleUsuari
 *  Comprova que l'article sigui del usuari logat.
 * @param string $idArticle
 * @return boolean Si l'article es del usuari retorna true en cas contrari retorna false.
 */
function autentificacioArticleUsuari($idArticle){
  $conexio=obrirBDD();
  session_start();
  $_SESSION['newsession'];
  if(!is_null($conexio)){
    //agafa la id del usuari a partir del correu.
    $setencia = "SELECT id FROM usuaris WHERE correu=:correu";
    $array=array(':correu' => $_SESSION['newsession']);
    $result=executarSentencia($setencia,$array,$conexio);


    //comproba que el article sigui del usuari.
    $setencia = "SELECT COUNT(*) FROM articles WHERE id_usuari=:id_usuari AND id=:id ";
    $array=array(':id_usuari' => $result[0]["id"],':id' => $idArticle);
    $result=executarSentencia($setencia,$array,$conexio);
    $conexio=tancarBDD($conexio);
    if(($result[0]['COUNT(*)'])==0){return false;}
    return true;
  }
}

/**
 * Summary of esborrarArticle
 * @param string $idArticle
 * @return void
 */
function esborrarArticle($idArticle){
  $conexio=obrirBDD();
  $setencia = "DELETE FROM articles WHERE id=:id";
  $array=array(':id' => $idArticle);
  $result=executarSentencia($setencia,$array,$conexio);
  $conexio=tancarBDD($conexio); 
}

/**
 * Summary of modificarArticle
 * @param string $idArticle
 * @param string $article
 * @return void
 */
function modificarArticle($idArticle,$article){
  $conexio=obrirBDD();
  $setencia = "UPDATE articles SET article = :article WHERE id = :id;";
  $array=array(':article' => $article,':id' => $idArticle);
  $result=executarSentencia($setencia,$array,$conexio);
  $conexio=tancarBDD($conexio); 
}


/**
 * Summary of canviarContrasenya
 * @param string $correu
 * @param string $password
 * @return void
 */
function canviarContrasenya($correu,$password){
  $conexio=obrirBDD();
  $setencia = "UPDATE usuaris SET contrasenya = :contrasenya WHERE correu = :correu ;";
  $array=array(':contrasenya' => $password,':correu' => $correu);
  $result=executarSentencia($setencia,$array,$conexio);
  $conexio=tancarBDD($conexio); 
  
}

/**
 * Summary of esborrarUsuari
 * @param string $correu
 * @return void
 */
function esborrarUsuari($correu){
  $conexio=obrirBDD();
  $setencia = 'SELECT id FROM usuaris WHERE correu =:correu';
  $array=array(':correu' => $correu);
  $result=executarSentencia($setencia,$array,$conexio);
  $id=$result[0]["id"];
  //Eliminen primer els articles del usuari.
  $setencia = 'DELETE FROM articles WHERE id_usuari = :userId';
  $array=array(':userId' => $id);
  executarSentencia($setencia,$array,$conexio);
  //I ara al usuari.
  $setencia = 'DELETE FROM usuaris WHERE id = :userId';
  $array=array(':userId' => $id);
  executarSentencia($setencia,$array,$conexio);
  $conexio=tancarBDD($conexio); 
  
}

function crearUsuariOauth($correu,$nom,$social){
  $conexio=obrirBDD();
    if(!is_null($conexio)){
      $setencia = "INSERT INTO usuaris (nom, correu, social_provider) VALUES (:nom, :correu, :social)";
      $array=array(':nom' => $nom, ':correu' => $correu, ':social' => $social);

      $result=executarSentencia($setencia,$array,$conexio);

      $conexio=tancarBDD($conexio);
    }


}

?>