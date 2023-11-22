<!-- Eric Rubio Sanchez -->
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">  
    <link rel="stylesheet" type="text/css" href="../Estils/estils.css">
    <script defer src="../Controlador/dropdown.js"></script> 
    <link rel="stylesheet" type="text/css" href="../Estils/estilForms.css">
    <title>Esborrar</title>
</head>
<body>
    <?php if( !isset( $_SESSION['newsession'])){session_start();} ?>
    <!-- Retorna a la pagina de articles si es tanca la sessió -->
    <?php if( !isset( $_SESSION['newsession'])){
	    header("Location: ../Vista/index.vista.php");
        exit();}
    ?>
    <?php require_once'../Vista/navbar.vista.php'; ?>
    <div class="container">
        <div>
        <h1 class="box">Esborrar</h1>
        </div>
        <div class="principalBox">
            <form action="../Controlador/esborrar.php" method="post">
                <br>
                <label>
                    ID Article:<input type="text" name="id_article" required value="<?php if(isset($idArticle)){echo $idArticle;}?>">
                </label>
                <br>
                <?php if (!empty($errors)):?>
                    <div><?php
                        echo "<p class='errors resultado'>".$errors."</p>";
                        ?>
                    </div>
                <?php endif ?>
                <?php if (!empty($correcte)):?>
                    <div><?php
                        echo "<p class='correcte resultado'>".$correcte."</p>";
                        ?>
                    </div>
                <?php endif ?>
                <br>
                <div>
                    <input type="submit" value="Enviar" onclick="return confirm('Estàs segur que vols esborrar?')">
                </div>
            </form>
                
        </div>
    </div>
</body>
</html>