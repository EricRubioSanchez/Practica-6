<?php
//agafa el arxiu actual en el que esta com a string.
$file = pathinfo($_SERVER['PHP_SELF'])['filename'];

//Ho comprova amb tots els arxius de vista per veure si es troba en algun.
$indexActive = $file == "index.vista" ? "active" : "";
$canviarContrasenyaActive = $file== "canviarContrasenya.vista" ? "active" : "";
$enregistrarseActive = $file == "enregistrarse.vista" ? "active" : "";
$enviarCorreuActive = $file == "enviarCorreu.vista" ? "active" : "";
$esborrarActive = $file == "esborrar.vista" ? "active" : "";
$inserirActive = $file == "inserir.vista" ? "active" : "";
$logarseActive = $file == "logarse.vista" ? "active" : "";
$modificarActive = $file == "modificar.vista" ? "active" : "";
$meusArticlesActive = $file == "meusArticles.vista" ? "active" : "";
$imatgeIndex= $file == "imatgeIndex.vista" ? "active" : "";
$imatgeInserir= $file == "imatgeInserir.vista" ? "active" : "";
?>