<?php
//Eric Rubio Sanchez
/**
 * Summary of tractarDades
 *  Aquesta funcio serveix per evitar l'injeccio de codi treient els espais, les '\' i convertint els caracters especial en entitats HTML.
 * @param String $data demana la dada sense tractar
 * @return String retorna la dada tractada
 */
function tractarDades($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Summary of iniciarSession
 *  Inicialitza les variables newsession i nom.
 * @param String $usuari el correu electronic que utilitzem com id.
 * @param String $nom el nom del usuari
 * @return void
 */
function iniciarSession($usuari,$nom){
    ini_set('session.gc_maxlifetime', 1500);
    ini_set('session.cookie-lifetime', 1500);
    session_start();
    $_SESSION["newsession"]=$usuari;
    $_SESSION["nom"]=$nom;
    header("Location: ../Vista/index.vista.php");
                exit();

}

?>