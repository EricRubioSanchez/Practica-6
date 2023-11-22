<?php
//Eric Rubio Sanchez
require_once("../Model/BDD.php");

/*-------------------------------------------------------FUNCIONS----------------------------------------------------*/

// Establim el numero de pagina en la que l'usuari es troba.
# si no troba cap valor, assignem la pagina 1.
/**
 * Summary of posicioPagina2
 *  retorna la el numero de la pagina en la que es troba l'usuari amb l'enllaç.
 * @return int
 */
function posicioPagina2() {
    $page=1;
    if ($_SERVER["REQUEST_METHOD"]=="GET"){
        //Intentem la variable del formulari page.
        if(isset($_GET["page"])){
            $page=$_GET["page"];
        //Intentem la variable de les cookies page.
        }else if(isset($_COOKIE["page2"])){
            $page=unserialize($_COOKIE['page2']);
        }else{
            //Si no es pot agafar posarem 1 per defecte.
            $page=1;
        }
    }
    $conexio=obrirBDD();
    $nPagines=calcularPagines2($conexio);
    $conexio=tancarBDD($conexio);
            //Si la agafem comprobem que estigui dintre del range de 0 i les pagines totals.
        //Si no ho esta va cap a la pagina 1.
    if($page>$nPagines || $page<1){
        $page=1;
    }
    else{
        setcookie('page2',serialize($page));
    }
    return $page;
}

/**
 * Summary of mostrarBotons
 *  Crea un text html amb la informacio d'on et porta cada buto. 
 * @return string
 */
function mostrarBotons2() {
    //Inicialitzem variables
    $posicioPagina2=posicioPagina2();
    $nArticlesPerPagina=getnArticlesPerPagina2();
    $botons="";
    $conexio=obrirBDD();
    $nPagines=calcularPagines2($conexio);
    $conexio=tancarBDD($conexio);

    //Si hi ha mes d'una pagina afegirem els botons.
    if($nPagines>1){
        $botons="<ul>";
            //Botons per anar cap enrere desabilitats si et trobas a la primera pagina
            if($posicioPagina2==1){
                $botons.='<li class="disabled">&laquo&laquo;</li>';
                $botons.='<li class="disabled">&laquo;</li>';
            }
            //Botons per anar cap enrere habilitats.
            else{
                $botons.='<li><a href="../Vista/meusArticles.vista.php?page='.(1).'&nArticlesPerPagina='.$nArticlesPerPagina.'">&laquo&laquo;</a></li>';
                $botons.='<li><a href="../Vista/meusArticles.vista.php?page='.($posicioPagina2-1).'&nArticlesPerPagina='.$nArticlesPerPagina.'">&laquo;</a></li>';}
            //Botons per moure de pagina.
            for ($i = 1; $i <= $nPagines; $i++) {
                if($i==$posicioPagina2){
                    $botons.='<li class="active"><a href="../Vista/meusArticles.vista.php?page='.$i.'&nArticlesPerPagina='.$nArticlesPerPagina.'">'.$i.'</a></li>';
                }else{
                    $botons.='<li><a href="../Vista/meusArticles.vista.php?page='.$i.'&nArticlesPerPagina='.$nArticlesPerPagina.'">'.$i.'</a></li>';
                }
            }
            //Botons per anar cap a la dreta desabilitats si et trobas a la ultima pagina
            if($posicioPagina2==$nPagines){
                $botons.='<li class="disabled">&raquo;</li>';
                $botons.='<li class="disabled">&raquo&raquo;</li>';
            }
            //Botons per anar cap a la dreta habilitats.
            else{
                $botons.='<li><a href="../Vista/meusArticles.vista.php?page='.($posicioPagina2+1).'&nArticlesPerPagina='.$nArticlesPerPagina.'">&raquo;</a></li>';
                $botons.='<li><a href="../Vista/meusArticles.vista.php?page='.($nPagines).'&nArticlesPerPagina='.$nArticlesPerPagina.'">&raquo&raquo;</a></li>';}
        $botons.="</ul>";
        return $botons;	
    }
}

if ($_SERVER["REQUEST_METHOD"]=="GET"&&isset($_GET["nArticlesPerPagina"])){
    $nArticlesPerPagina=$_GET["nArticlesPerPagina"];
    if($nArticlesPerPagina>10 || $nArticlesPerPagina<1){
        $nArticlesPerPagina=5;
    }else{
        setcookie('nArticlesPerPagina2',serialize($nArticlesPerPagina));
    }}

// definim quants post per pagina volem carregar.
/**
 * Summary of getnArticlesPerPagina2
 *  Si el metod es GET comproba que s'hagi enviat el formulari si no s'ha enviat es 5 per defecte.
 * @return int
 */
function getnArticlesPerPagina2(){
    $nArticlesPerPagina=5;
    if(isset($_COOKIE["nArticlesPerPagina2"])){
        $nArticlesPerPagina=unserialize($_COOKIE['nArticlesPerPagina2']);
    }
    //Si la agafem i es mes gran de 10 i mes petit de 1 posem 5 per defecte.
    if($nArticlesPerPagina>10 || $nArticlesPerPagina<1){
        $nArticlesPerPagina=5;
    }
        
    return $nArticlesPerPagina;
}

/**
 * Summary of calcularPagines2
 *  executa la sentencia i la divideix amb el numero de articles per pagina.
 * @param PDO $connexio
 * @return int
 */
function calcularPagines2($conexio){
    $nArticlesPerPagina = getnArticlesPerPagina2();
    if(!is_null($conexio)){
        

        try{
            //Agafem la id del usuari per buscarla en els articles.
            $setencia = "SELECT id FROM usuaris WHERE correu=:correu";
            $array=array(':correu' => $_SESSION['newsession']);
             $result=executarSentencia($setencia,$array,$conexio);

            // Calculem el total d'articles per a poder conèixer el número de pàgines de la paginació
            $setencia = "SELECT * FROM articles WHERE id_usuari=:id;";
            $array=array(':id' => $result[0]["id"]);
            $result=executarSentencia($setencia,$array,$conexio);

            // Comprovem que hagui articles, en cas contrari, rediriguim
            if (is_null($result)){return 1;}

            // Calculem el numero de pagines que tindrà la paginació. Llavors hem de dividir el total d'articles entre els POSTS per pagina
            return ceil((count($result)/$nArticlesPerPagina));
        }catch(PDOException $e){
            echo $e;
        }
    }
}


// Revisem des de quin article anem a carregar, depenent de la pagina on es trobi l'usuari.
# Comprovem si la pagina en la que es troba es més gran d'1, sino carreguem des de l'article 0.
# Si la pagina es més gran que 1, farem un càlcul per saber des de quin post carreguem
/**
 * Summary of mostrarPerPagina2
 *  executem la sentencia per mostrar tots els articles dintre del rang de la teva pagina 
 *  multiplicat pel nombre de articles per pagina.
 *  Ho retorna amb text html
 * @return string|null
 */
function mostrarPerPagina2(){
    $conexio=obrirBDD();
    $nPagina=posicioPagina2();
    $nArticlesPerPagina = getnArticlesPerPagina2();
    $articlesPerPagina="";
    if(!is_null($conexio)){
        /*
        for ($i=0; $i <$nArticlesPerPagina ; $i++) {         
            $setencia="SELECT a.id, a.article,u.nom FROM articles a JOIN usuaris u ON a.id_usuari = u.Id WHERE a.id =:id ";
            $array=array(':id' =>  ($nPagina * $nArticlesPerPagina)-($i));
        */
        try{
            $setencia="SELECT a.id, a.article,u.nom FROM articles a JOIN usuaris u ON a.id_usuari = u.Id WHERE u.correu= :correu ORDER BY id;";
            $array=array(':correu' => $_SESSION['newsession']);
            $result=executarSentencia($setencia,$array,$conexio);
            $conexio=tancarBDD($conexio);
            if(empty($result)){
                return;
            }
            $articlesPerPagina.="<ul>";
            $result=array_slice($result,($nPagina*$nArticlesPerPagina-$nArticlesPerPagina),($nArticlesPerPagina));
            //Creacio del text HTML
            foreach ($result as $article) {
                foreach($article as $key => $value) {
                    if ($key=="id") {
                        $articlesPerPagina.="<li>".$value;
                    }
                    else if($key=="article"){
                        $articlesPerPagina.=".- ".$value;
                    }
                    else if($key=="nom"){
                        $articlesPerPagina.="<br> Autor: ".$value."</li>";
                    }
                }
            }
            
            $articlesPerPagina.="</ul>";
            return $articlesPerPagina;
        }catch(PDOException $e){
            echo $e;
            $conexio=tancarBDD($conexio);
        }
    }
}
require_once '../Vista/meusArticles.vista.php';

?>