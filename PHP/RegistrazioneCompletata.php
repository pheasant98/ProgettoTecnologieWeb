<?php

require_once ('Controller/LoginController.php');

session_start();

if (LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/RegistrazioneCompletata.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);

echo $document;

?>
