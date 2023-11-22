<?php 
// Database configuration 
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'github'); 
define('DB_USER_TBL', 'users'); 
 
// GitHub API configuration 
define('CLIENT_ID', 'Iv1.19f822d654bcae24'); 
define('CLIENT_SECRET', '2e071f619759b76453bc2fe64f9adeb39b589403'); 
define('REDIRECT_URL', 'http://localhost/UF1/Practica%205/Controlador/github/index.php'); 
 
// Start session 
if(!session_id()){ 
    session_start(); 
} 
 
// Include Github client library 
require_once 'Github_OAuth_Client.php'; 
 
// Initialize Github OAuth client class 
$gitClient = new Github_OAuth_Client(array( 
    'client_id' => CLIENT_ID, 
    'client_secret' => CLIENT_SECRET, 
    'redirect_uri' => REDIRECT_URL 
)); 
 
// Try to get the access token 
if(isset($_SESSION['access_token'])){ 
    $accessToken = $_SESSION['access_token']; 
}