<?php
require_once'../Controlador/navbar.php';
?>
<nav>
		<ul>
			<li class=<?php echo($indexActive) ?>><a href="../Vista/index.vista.php">Articles</a></li>
			<?php if( isset( $_SESSION['newsession'])):?>
				<li class=<?php echo($inserirActive) ?>><a href="../Vista/inserir.vista.php">Inserir</a></li>
				<li class=<?php echo($modificarActive) ?>><a href="../Vista/modificar.vista.php">Modificar</a></li>
				<li class=<?php echo($esborrarActive) ?>><a href="../Vista/esborrar.vista.php">Esborrar</a></li>
				<li class=<?php echo($esborrarActive) ?>><a href="../Vista/imatgeIndex.vista.php">Imatges</a></li>
				<li class=<?php echo($esborrarActive) ?>><a href="../Vista/imatgeInserir.vista.php">Afegir Imatge</a></li>
				<li class="logs"><div class="dropdown">
					<a onclick="myFunction()" class="dropbtn"><?php echo($_SESSION['nom'] );?></a>
					<div id="myDropdown" class="dropdown-content">
						<ul>
						<li><a href="../Controlador/logout.php">Sortir</a></li>
						<li class=<?php echo($canviarContrasenyaActive) ?>><a href="../Vista/canviarContrasenya.vista.php">Canviar Contrasenya</a></li>
						<li><a href="../Controlador/esborrarUsuari.php" onclick="return confirm('Estàs segur que vols esborrar el teu conte?')">Donar-se de baixa</a></li>
						<li class=<?php echo($meusArticlesActive) ?>><a href="../Vista/meusArticles.vista.php">Els meus articles</a></li>
						</ul>
					</div>
				</div>
				</li>
			<?php else: ?>
				<li class="logs <?php echo($logarseActive) ?>"><a href="../Vista/logarse.vista.php">Logar-in</a></li>
				<li class="logs <?php echo($enregistrarseActive) ?>"><a href="../Vista/enregistrarse.vista.php">Sign-up</a></li>
            <?php endif; ?>
		</ul>
	</nav>