<?php

session_start();

require_once ('Controller/LoginController.php');

$document = file_get_contents('../HTML/Contatti.html');
$login = LoginController::getAuthenticationMenu();
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);

echo $document;

?>
