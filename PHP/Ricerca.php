<?php

require_once ('Controller/LoginController.php');

session_start();

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Cerca' || !isset($_POST['filter']) || !isset($_POST['search'])) {
    header('Location: Errore.php');
}

if ($_POST['filter'] === 'Opera') {
    require_once ('Controller/ArtworksController.php');

    $controller = new ArtworksController();

} else if ($_POST['filter'] === 'Evento') {
    require_once ('Controller/EventsController.php');

    $controller = new EventsController();
} else {
    header('Location: Errore.php');
}



?>
