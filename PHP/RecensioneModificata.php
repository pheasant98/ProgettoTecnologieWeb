<?php

require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser() || LoginController::isAdminUser()) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/RecensioneModificata.html');
$login = LoginController::getAuthenticationMenu();

$reviews_page = '?page=' . $_SESSION['page'];
$review_id = '?id=' . $_SESSION['review_id'];

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='listBreadcrumbsPlaceholder'/>", $reviews_page, $document);
$document = str_replace("<span id='modifyBreadcrumbsPlaceholder'/>", $review_id, $document);
$document = str_replace("<span id='reviewPlaceholder'/>", $_SESSION['review_title'], $document);

unset($_SESSION['review_title']);
unset($_SESSION['review_id']);
unset($_SESSION['page']);

echo $document;

?>
