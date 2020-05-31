<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Controller/LoginController.php');
require_once ('Controller/ReviewsController.php');

session_start();

if (!LoginController::isAuthenticatedUser() || LoginController::isAdminUser() || !isset($_SESSION['previous_page'])) {
    header('Location: Errore.php');
}

$message = '';

if (isset($_POST['submit']) && $_POST['submit'] === 'Invia') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $reviewsController = new ReviewsController();
    $message = $reviewsController->addReview($title, $content, $_SESSION['username']);
    unset($reviewsController);
}

if ($message === '') {
    $title = '';
    $content = '';
}

$document = file_get_contents('../HTML/LasciaUnaRecensione.html');
$login = LoginController::getAuthenticationMenu();

$breadcrumbs = '';
if ($_SESSION['previous_page'] === 'AreaPersonale') {
    $breadcrumbs = '<a href="AreaPersonale.php" title="Area personale" aria-label="Vai alla pagina area personale" tabindex="10">Area personale</a>';
} else if ($_SESSION['previous_page'] === 'CosaDiconoDiNoi') {
    $breadcrumbs = '<a href="CosaDiconoDiNoi.php" title="Cosa dicono di noi" aria-label="Vai alla pagina cosa dicono di noi" tabindex="10">Cosa dicono di noi</a>';
}

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='breadcrumbsPlaceholder'/>", $breadcrumbs, $document);
$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);
$document = str_replace("<span id='titleValuePlaceholder'/>", $title, $document);
$document = str_replace("<span id='contentValuePlaceholder'/>", $content, $document);

echo $document;

?>
