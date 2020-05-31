<?php

session_start();

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Rimuovi' || !isset($_SESSION['page']) || !isset($_SESSION['filter_content']) || !isset($_SESSION['filter_content_type'])) {
    header('Location: Errore.php');
}

if ($_POST['type'] === 'Evento') {
    require_once('Controller/EventsController.php');
    $controller = new EventsController();

    $event = $controller->getEvent($_POST['id']);
    $controller->deleteEvent($_POST['id']);

    $_SESSION['deleted'] = $event['Titolo'];
} else if ($_POST['type'] === 'Opera') {
    require_once('Controller/ArtworksController.php');
    $controller = new ArtworksController();

    $artwork = $controller->getArtwork($_POST['id']);
    $controller->deleteArtwork($_POST['id']);

    $_SESSION['deleted'] = $artwork['Titolo'];
}

unset($controller);

$_SESSION['deleted_type'] = $_POST['type'];

$page = $_SESSION['page'];
$filter_content = $_SESSION['filter_content'];
$filter_content_type = $_SESSION['filter_content_type'];
unset($_SESSION['page']);
unset($_SESSION['filter_content']);
unset($_SESSION['filter_content_type']);

header('Location: GestioneContenuti.php?page=' . $page . '&amp;filterContent='. $filter_content . '&amp;filterContentType='. $filter_content_type);

?>
