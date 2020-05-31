<?php

session_start();

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Rimuovi' || !isset($_SESSION['page']) || !isset($_SESSION['filter_content']) || !isset($_SESSION['filter_content_type'])) {
    header('Location: Errore.php');
}

if ($_POST['type'] === 'Evento') {
    require_once('Controller/EventsController.php');
    $controller = new EventsController();
    $controller->deleteEvent($_POST['id']);
} else if ($_POST['type'] === 'Opera') {
    require_once('Controller/ArtworksController.php');
    $controller = new ArtworksController();
    $controller->deleteArtwork($_POST['id']);
}

unset($controller);

$_SESSION['deleted'] = $_POST['id'];
$_SESSION['deleted_type'] = $_POST['type'];

$page = $_SESSION['page'];
$filter_content = $_SESSION['filter_content'];
$filter_content_type = $_SESSION['filter_content_type'];
unset($_SESSION['page']);
unset($_SESSION['filter_content']);
unset($_SESSION['filter_content_type']);

header('Location: GestioneContenuti.php?page=' . $page . '&amp;filterContent='. $filter_content . '&amp;filterContentType='. $filter_content_type);

?>
