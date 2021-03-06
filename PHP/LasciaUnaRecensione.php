<?php

require_once ('Controller/LoginController.php');
require_once ('Controller/ReviewsController.php');
require_once ('Utilities/InputCheckUtilities.php');

session_start();

if (!LoginController::isAuthenticatedUser() || LoginController::isAdminUser() || !isset($_SESSION['previous_page'])) {
    header('Location: Errore.php');
}

$message = '';

if (isset($_POST['submit']) && $_POST['submit'] === 'Invia') {
    if (isset($_POST['title'])) {
        $title = $_POST['title'];
    } else {
        $title = '';
    }

    if (isset($_POST['content'])) {
        $content = $_POST['content'];
    } else {
        $content = '';
    }

    $reviewsController = new ReviewsController();
    $message = $reviewsController->addReview($title, $content, $_SESSION['username']);
    unset($reviewsController);
}

if ($message === '' || $message === '<p class="success">Recensione ' . InputCheckUtilities::prepareStringForDisplay((InputCheckUtilities::prepareStringForChecks($title))) . ' inserita correttamente</p>') {
    $title = '';
    $content = '';
}

$document = file_get_contents('../HTML/LasciaUnaRecensione.html');
$login = LoginController::getAuthenticationMenu();

$breadcrumbs = '';
if (isset($_SESSION['previous_page'])) {
    if ($_SESSION['previous_page'] === 'AreaPersonale') {
        $breadcrumbs = '<a href="AreaPersonale.php" title="Vai alla pagina dell\'area personale" aria-label="Vai alla pagina dell\'area personale" rel="nofollow">Area personale</a>';
    } else if ($_SESSION['previous_page'] === 'CosaDiconoDiNoi') {
        $page = '?page=' . $_SESSION['reviews_page'];
        $breadcrumbs = '<a href="CosaDiconoDiNoi.php' . $page . '" title="Vai alla pagina cosa dicono di noi" aria-label="Vai alla pagina cosa dicono di noi">Cosa dicono di noi</a>';
    }
} else {
    $breadcrumbs = '<a href="CosaDiconoDiNoi.php?page=1" title="Vai alla pagina cosa dicono di noi" aria-label="Vai alla pagina cosa dicono di noi">Cosa dicono di noi</a>';
}

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='breadcrumbsPlaceholder'/>", $breadcrumbs, $document);
$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);
$document = str_replace("<span id='titleValuePlaceholder'/>", $title, $document);
$document = str_replace("<span id='contentValuePlaceholder'/>", $content, $document);

echo $document;

?>
