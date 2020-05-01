<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once ('Controller/LoginController.php');

$document = file_get_contents('../HTML/Contatti.html');
$login = LoginController::getAuthenticationMenu();
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);

echo $document;

?>
