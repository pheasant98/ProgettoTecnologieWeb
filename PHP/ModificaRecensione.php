<?php

require_once ('Controller/LoginController.php');
require_once ('Controller/ReviewsController.php');

session_start();

if (!LoginController::isAuthenticatedUser() || LoginController::isAdminUser() || !isset($_GET['id']) || !isset($_SESSION['reviewPage'])) {
    header('Location: Errore.php');
}

$message = '';

$reviews_controller = new ReviewsController();
$review = $reviews_controller->getReview($_GET['id']);

$title = $review['Oggetto'];
$description = $review['Contenuto'];

if (isset($_POST['submit']) && $_POST['submit'] === 'Modifica') {
    $title = $_POST['title'];
    $description = $_POST['content'];

    $message = $reviews_controller->updateReview($_GET['id'], $title, $description, $_SESSION['username']);

    if($message === '') {
        $_SESSION['review_title'] = $_POST['title'];
        $_SESSION['review_id'] = $_GET['id'];

        header('Location: RecensioneModificata.php');
    }

    unset($reviews_controller);
}

$document = file_get_contents('../HTML/ModificaRecensione.html');
$login = LoginController::getAuthenticationMenu();

$breadcrumbs = '?page=' . $_SESSION['reviewPage'];

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='breadcrumbsPlaceholder'/>", $breadcrumbs, $document);
$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);
$document = str_replace("<span id='titleValuePlaceholder'/>", $title, $document);
$document = str_replace("<span id='contentValuePlaceholder'/>", $description, $document);

echo $document;

?>