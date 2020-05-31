<?php

require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser() && !LoginController::isAdminUser()) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/OperaModificata.html');
$login = LoginController::getAuthenticationMenu();
$artwork_title = $_SESSION['artwork_title'];

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='artworkTitlePlaceholder'/>", $artwork_title, $document);

echo $document;

?>
