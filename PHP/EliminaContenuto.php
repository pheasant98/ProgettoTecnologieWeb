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

    if (copy('../' . $artwork['Immagine'], '../_' . $artwork['Immagine'])) {
        if (unlink('../' . $artwork['Immagine'])) {
            if ($controller->deleteArtwork($_POST['id'])) {
                $_SESSION['contentDeletedError'] = true;
                unlink('../_' . $artwork['Immagine']);
                echo 'all good';
            } else {
                $_SESSION['contentDeletedError'] = false;
                rename('../_' . $artwork['Immagine'], '../' . $artwork['Immagine']);
                echo 'delete not good';
            }
        } else {
            $_SESSION['contentDeletedError'] = false;
            unlink('../_' . $artwork['Immagine']);
            echo 'delete image not good';
        }
    } else {
        $_SESSION['contentDeletedError'] = false;
        echo 'copy not good';
    }

    $_SESSION['contentDeleted'] = $artwork['Titolo'];
}

unset($controller);

$_SESSION['deleted_type'] = $_POST['type'];

$page = $_SESSION['contentPage'];
$filter_content = $_SESSION['filter_content'];
$filter_content_type = $_SESSION['filter_content_type'];

// header('Location: GestioneContenuti.php?page=' . $page . '&amp;filterContent='. $filter_content . '&amp;filterContentType='. $filter_content_type);

?>
