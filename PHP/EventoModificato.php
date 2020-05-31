<?php

require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser() && !LoginController::isAdminUser()) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/EventoModificato.html');
$login = LoginController::getAuthenticationMenu();
$event_title = $_SESSION['event_title'];

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='eventTitlePlaceholder'/>", $event_title, $document);

echo $document;

?>
