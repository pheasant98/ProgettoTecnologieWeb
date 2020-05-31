<?php

require_once ('Controller/LoginController.php');

session_start();

if (LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/RegistrazioneCompletata.html');
$login = LoginController::getAuthenticationMenu();
$username = $_SESSION['username'];

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='usernamePlaceholder'/>", $username, $document);

echo $document;

?>
