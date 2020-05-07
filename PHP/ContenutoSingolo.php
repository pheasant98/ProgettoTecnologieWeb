<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['type']) || (($_GET['type'] !== 'Opera') && ($_GET['type'] !== 'Evento'))) {
    header('Location: Error.php');
}

session_start();

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/ContenutoSingolo.html');

if ($_GET['type'] === 'Opera') {
    require_once ('Controller/ArtworksController.php');
    $controller = new ArtworksController();
    $breadcrumbs_previous_link = '<a href="Contenuti.php?type=Opere" title="Opere" aria-label="Vai alla pagina Opere" tabindex="10" xml:lang="en">Opere</a>';
} else {
    require_once ('Controller/EventsController.php');
    $controller = new EventsController();
    $breadcrumbs_previous_link = '<a href="Contenuti.php?type=Eventi" title="Eventi" aria-label="Vai alla pagina Eventi" tabindex="10" xml:lang="en">Eventi</a>';
}

$login = LoginController::getAuthenticationMenu();

if($_GET['type'] === 'Opera') {
    $single_content = $controller->getArtwork($_GET['id']);
} else {
    $single_content = $controller->getEvent($_GET['id']);
}

$document = str_replace("<span id='titlePlaceholder'/>", $_GET['type'], $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='breadcrumbsLinkPlaceholder'/>", $breadcrumbs_previous_link, $document);
$document = str_replace("<span id='singleContentPlaceholder'/>", $single_content, $document);

echo $document;

?>
