<?php 
            // Include configuration file 
            require_once '../../Vista/config.php'; 
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
                    

                    require_once("../session.php");
                    ini_set('session.gc_maxlifetime', 1500);
                    ini_set('session.cookie-lifetime', 1500);
                    session_start();
                    $_SESSION["newsession"]=$gitUserData['username']."@sapalomera.cat";
                    $_SESSION["nom"]=$gitUserData['username'];
                    header("Location: ../oauth.php?correu=".$_SESSION["newsession"]."&"."nom=".$_SESSION["nom"]."&"."social=Github");
                    exit();

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