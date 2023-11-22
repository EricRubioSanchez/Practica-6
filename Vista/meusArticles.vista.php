<!-- Eric Rubio Sanchez -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
	<script defer src="../Controlador/dropdown.js"></script>  
	<link rel="stylesheet" href="../Estils/estils.css"> <!-- feu referència al vostre fitxer d'estils -->
	<title>Articles</title>
</head>
<body>
	<?php include_once'../Controlador/meusArticles.php';
	session_start();
	require_once'../Vista/navbar.vista.php';?>
	
    <?php if( !isset( $_SESSION['newsession'])){
	    header("Location: ../Vista/index.vista.php");
        exit();}
    ?>

	<div class="contenidor">
		<h1>Articles</h1>

		<form action="../Controlador/index.php">
  			<label for="nArticlesPerPagina">Eligeix el nombre de articles per pàgina:</label>
  			<select name="nArticlesPerPagina" id="nArticlesPerPagina">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5" selected="selected">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select>
			<input type="submit" value="Enviar">
		</form>

		<section class="articles"> <!--aqui guardem els articles-->
			<?php 
			$articles= mostrarPerPagina2();
			if($articles){
			echo $articles;
			}else if( isset( $_SESSION['newsession'])){
				echo('No hi ha posts, <a href="../Vista/inserir.vista.php">afegeix un</a> ');
			}
			?>
		</section>

		<section class="paginacio"> <!--aqui guardem els botons-->
		<?php 
			$botons= mostrarBotons2();
			echo $botons;
			?>
		</section>
	</div>
</body>
</html>