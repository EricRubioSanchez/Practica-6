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
    <title>Canviar Contrasenya</title>
</head>
<body>
    <?php if ($_SERVER["REQUEST_METHOD"]=="GET"&& isset($_GET["correu"])){
        session_start();
        $correu=$_GET["correu"];
        $token=$_GET["token"];
        $_SESSION["correu"]=$correu;
        $_SESSION["token"]=$token;
    } ?>
    <!-- Retorna a la pagina de articles si es tanca la sessió -->
    <?php require_once'../Vista/navbar.vista.php'; ?>
    <div class="container">
        <div>
        <h1 class="box">Canviar Contrasenya</h1>
        </div>
        <div class="principalBox">
            <form action="../Controlador/canviarContrasenya.php" method="post">
                <br>
                <label>Nova contrasenya:
                    <input type="password" name="password" required value="<?php if(isset($password)){echo $password;}?>">
                </label>
                <br>
                <label> Torna a introduir la nova contrasenya
                    <input type="password" name="password2" required value="<?php if(isset($password2)){echo $password2;}?>">
                </label>
                <br>
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
                    <input type="submit" value="Enviar" onclick="return confirm('Estàs segur?')">
                </div>
            </form>
                
        </div>
    </div>
</body>
</html>