<?php

require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser() && !LoginController::isAdminUser()) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/OperaInserita.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);

echo $document;

?>
