<?php

require_once ('Controller/LoginController.php');

session_start();

if ((!LoginController::isAuthenticatedUser() && !LoginController::isAdminUser()) || !isset($_SESSION['event_title']) || !isset($_SESSION['contentPage']) || !isset($_SESSION['event_id'])) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/EventoModificato.html');
$login = LoginController::getAuthenticationMenu();

$events_page = '?page=' . $_SESSION['contentPage'];
$event_id = '?id=' . $_SESSION['event_id'];

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='listBreadcrumbsPlaceholder'/>", $events_page, $document);
$document = str_replace("<span id='modifyBreadcrumbsPlaceholder'/>", $event_id, $document);
$document = str_replace("<span id='eventTitlePlaceholder'/>", $_SESSION['event_title'], $document);

echo $document;

?>
