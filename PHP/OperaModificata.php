<?php

require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser() || !LoginController::isAdminUser() || !isset($_SESSION['artwork_title']) || !isset($_SESSION['artwork_id']) || !isset($_SESSION['contentPage'])) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/OperaModificata.html');
$login = LoginController::getAuthenticationMenu();

$artworks_page = '?page=' . $_SESSION['contentPage'];
$artwork_id = '?id=' . $_SESSION['artwork_id'];

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='listBreadcrumbsPlaceholder'/>", $artworks_page, $document);
$document = str_replace("<span id='modifyBreadcrumbsPlaceholder'/>", $artwork_id, $document);
$document = str_replace("<span id='artworkTitlePlaceholder'/>", $_SESSION['artwork_title'], $document);

echo $document;

?>
