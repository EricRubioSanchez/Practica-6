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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<title>Log-IN</title>



</head>
<body>
    
    <?php require_once'../Vista/navbar.vista.php';
    include '../vendor/hybridauth/src/autoload.php'; ?>

    

	<form action="../Controlador/logarse.php" method="post">
            <label><h1>Logarse</h1></label>
            <br>
            <label>Correu electronic:
                <input type="email" name="correu" maxlength="30" minlength="4" required value="<?php if(isset($correu)){echo $correu;}?>">
            </label>
            <br>
            <label>Contrasenya:
                <input type="password" name="password" required value="<?php if(isset($password)){echo $password;}?>">
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
            <br>
            
            <?php 
            if(!isset($_SESSION["contrasenyaErronea"])){session_start();}
            
            if(isset($_SESSION["contrasenyaErronea"])){
            $contrasenyaErronea= $_SESSION["contrasenyaErronea"];
            }
            if (isset($contrasenyaErronea) && $contrasenyaErronea>=3):?>
                <div class="g-recaptcha" data-sitekey="6LdnHfAoAAAAAO8zdqxXLTWbI-hFsrOb-edlEAUn">
                </div>
            <?php endif ?>
            
            <input type="submit" value="Enviar">
            <a href="../Vista/enviarCorreu.vista.php">Recuperar Contrasenya</a>

            
            <div id="g_id_onload"
                data-client_id="287632858042-td5pnbaha5lmt20i0ruede803qk973c8.apps.googleusercontent.com"
                data-callback="onSignIn">
                
            </div>
            <div class="g_id_signin" data-type="standard"></div>
            
            <?php 
            // Include configuration file 
            require_once 'config.php'; 
            // Include and initialize user class 
            //require_once 'User.class.php'; 
            //$user = new User(); 
            //print_r($user);
            if(isset($accessToken)){ 
                // Get the user profile data from Github 
                $gitUser = $gitClient->getAuthenticatedUser($accessToken);
                if(!empty($gitUser)){ 
                    // Getting user profile details 
                    $gitUserData = array(); 
                    $gitUserData['oauth_uid'] = !empty($gitUser->id)?$gitUser->id:'No'; 
                    $gitUserData['name'] = !empty($gitUser->name)?$gitUser->name:'No'; 
                    $gitUserData['username'] = !empty($gitUser->login)?$gitUser->login:'No'; 
                    $gitUserData['email'] = !empty($gitUser->email)?$gitUser->email:'No'; 
                    $gitUserData['location'] = !empty($gitUser->location)?$gitUser->location:'No'; 
                    $gitUserData['picture'] = !empty($gitUser->avatar_url)?$gitUser->avatar_url:'No'; 
                    $gitUserData['link'] = !empty($gitUser->html_url)?$gitUser->html_url:'No'; 
                    
                    // Insert or update user data to the database 
                    $gitUserData['oauth_provider'] = 'github'; 
                    //$userData = $user->checkUser($gitUserData); 
            
                    // Storing user data in the session 
                    //$_SESSION['userData'] = $userData; 
            
                    // Render Github profile data 
                    /*
                    $output     = '<h2>GitHub Account Details</h2>'; 
                    $output .= '<div class="ac-data">'; 
                    $output .= '<img src="'.$gitUserData['picture'].'">'; 
                    $output .= '<p><b>ID:</b> '.$gitUserData['oauth_uid'].'</p>'; 
                    $output .= '<p><b>Name:</b> '.$gitUserData['name'].'</p>'; 
                    $output .= '<p><b>Login Username:</b> '.$gitUserData['username'].'</p>'; 
                    $output .= '<p><b>Email:</b> '.$gitUserData['email'].'</p>'; 
                    $output .= '<p><b>Location:</b> '.$gitUserData['location'].'</p>'; 
                    $output .= '<p><b>Profile Link:</b> <a href="'.$gitUserData['link'].'" target="_blank">Click to visit GitHub page</a></p>';
                    */ 
                    //$output = '<p>Logout from <a href="logout.php">GitHub</a></p>'; 
                    //$output .= '</div>'; 
                    $output="";
                    
                    $_SESSION["newsession"]=$gitUserData['username']."@sapalomera.cat";
                    $_SESSION["nom"]=$gitUserData['username'];

                    require_once("../Controlador/session.php");
                    iniciarSession($gitUserData['username']."@sapalomera.cat",$gitUserData['username']);

                }else{ 
                    $output = '<h3 style="color:red">Something went wrong, please try again!</h3>'; 
                }  
            }elseif(isset($_GET['code'])){ 
                // Verify the state matches the stored state 
                if(!$_GET['state'] || $_SESSION['state'] != $_GET['state']) { 
                    header("Location: ".$_SERVER['PHP_SELF']); 
                } 
                
                // Exchange the auth code for a token 
                $accessToken = $gitClient->getAccessToken($_GET['state'], $_GET['code']); 
            
                $_SESSION['access_token'] = $accessToken; 
            
                header('Location: ./'); 
            }else{ 
                // Generate a random hash and store in the session for security 
                $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']); 
                
                // Remove access token from the session 
                unset($_SESSION['access_token']); 
            
                // Get the URL to authorize 
                $authUrl = $gitClient->getAuthorizeURL($_SESSION['state']); 
                
                // Render Github login button 
                $output = '<a href="'.htmlspecialchars($authUrl).'">Iniciar Session con Github</a>'; 
            } 
            ?>

            <div class="container">
                <!-- Display login button / GitHub profile information -->
                <?php echo $output; ?>
            </div>
        </form>
</body>
</html>
<script src="../Controlador/signin.mjs"></script>
<script src="https://accounts.google.com/gsi/client" async defer></script>
