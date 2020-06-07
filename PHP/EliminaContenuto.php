<?php

session_start();

require_once ('Controller/LoginController.php');

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Rimuovi' || !isset($_SESSION['contentPage']) || !isset($_SESSION['filter_content']) || !isset($_SESSION['filter_content_type']) || !LoginController::isAdminUser()) {
    header('Location: Errore.php');
}

if ($_POST['type'] === 'Evento') {
    require_once('Controller/EventsController.php');
    $controller = new EventsController();

    $event = $controller->getEvent($_POST['id']);
    if ($controller->deleteEvent($_POST['id'])) {
        $_SESSION['contentDeletedError'] = true;
    } else {
        $_SESSION['contentDeletedError'] = false;
    }

    $_SESSION['contentDeleted'] = $event['Titolo'];
} else if ($_POST['type'] === 'Opera') {
    require_once('Controller/ArtworksController.php');
    $controller = new ArtworksController();

    $artwork = $controller->getArtwork($_POST['id']);

    if (unlink('../' . $artwork['Immagine'])) {
        if ($controller->deleteArtwork($_POST['id'])) {
            $_SESSION['contentDeletedError'] = true;
        } else {
            $_SESSION['contentDeletedError'] = false;
        }
    } else {
        $_SESSION['contentDeletedError'] = false;
    }

    $_SESSION['contentDeleted'] = $artwork['Titolo'];
}

unset($controller);

$_SESSION['deleted_type'] = $_POST['type'];


$content_count = $_SESSION['content_number_count'];
$offset = ($_SESSION['contentPage'] - 1) * 5;
if ($offset === ($content_count - 1)) {
    $page = $_SESSION['contentPage'] - 1;
} else {
    $page = $_SESSION['contentPage'];
}

$filter_content = $_SESSION['filter_content'];
$filter_content_type = $_SESSION['filter_content_type'];

header('Location: GestioneContenuti.php?page=' . $page . '&amp;filterContent='. $filter_content . '&amp;filterContentType='. $filter_content_type);

?>
