<!-- Eric Rubio Sanchez -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
    <script defer src="../Controlador/dropdown.js"></script>   
	<link rel="stylesheet" href="../Estils/estils.css"> <!-- feu referència al vostre fitxer d'estils -->
    <link rel="stylesheet" href="../Estils/estilForms.css"> <!-- feu referència al vostre fitxer d'estils -->
	<title>Recuperar Contrasenya</title>
</head>
<body>
    <?php require_once'../Vista/navbar.vista.php'; ?>
	<form action="../Controlador/enviarCorreu.php" method="post">
            <label><h1>Recuperar Contrasenya</h1></label>
            <br>
            <label>Correu electronic:
                <input type="email" name="correu" maxlength="30" minlength="4" required value="<?php if(isset($correu)){echo $correu;}?>">
            </label>
            <br>
            <?php if (!empty($errors)):?>
                <div><?php
                    echo "<p class='errors'>".$errors."</p>";
                    ?>
                </div>
            <?php endif ?>
            <?php if (!empty($correcte)):?>
                <div><?php
                    echo "<p class='correcte'>".$correcte."</p>";
                    ?>
                </div>
            <?php endif ?>
            <input type="submit" value="Enviar">
        </form>
</body>
</html>