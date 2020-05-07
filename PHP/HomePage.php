<?php

require_once('Controller/LoginController.php');

session_start();

$document = file_get_contents('../HTML/HomePage.html');
$login = LoginController::getAuthenticationMenu();
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);

echo $document;

?>
