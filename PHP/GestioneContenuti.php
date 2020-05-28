<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Controller/ArtworksController.php');
require_once ('Controller/EventsController.php');
require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$artworks_controller = new ArtworksController();
$events_controller = new EventsController();
$artwork_count = $artworks_controller->getArtworksCount();
$event_count = $events_controller->getEventsCount();

if($artwork_count == 1) {
    $artworks_number_found = '<p> È stata trovata ' . $artwork_count . ' opera. </p>';
} else {
    $artworks_number_found = '<p> Sono state trovate ' . $artwork_count . ' opere. </p>';
}
if($event_count == 1) {
    $events_number_found = '<p> È stato trovato ' . $event_count . ' evento. </p>';
} else {
    $events_number_found = '<p> Sono stati trovati ' . $event_count . ' eventi. </p>';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > (($artwork_count + $event_count) / 5))) {
    header('Location: Errore.php');
} else {
    $page = $_GET['page'];
}

$offset = ($page - 1) * 5;

if ((($artwork_count - $offset) < 5) && (($artwork_count - $offset) > 0)) {
    if($event_count > 0) {
        $contents_list = $artworks_controller->getArtworksTitle('', $offset, true);
        $events_offset = ($page * 5) - $artwork_count;
        $contents_list .= $events_controller->getEventsTitle('', 0, $artwork_count, $events_offset);
    } else {
        $contents_list = $artworks_controller->getArtworksTitle('', $offset);
    }
} elseif ($offset > $artwork_count) {
    $events_offset = $offset - $artwork_count;
    $contents_list = $events_controller->getEventsTitle('', $events_offset, $offset);
} else {
    $contents_list = $artworks_controller->getArtworksTitle('', $offset);
}

unset($artworks_controller);
unset($events_controller);

$previous_contents = '';
$next_contents = '';

if ($page > 1) {
    $previous_contents = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '" title="Utenti precedenti" role="button" aria-label="Torna ai contenuti precedenti"> &lt; Precedenti</a>';
}

if (($page * 5) < ($artwork_count + $event_count)) {
    $next_contents = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '" title="Utenti successivi" role="button" aria-label="Vai ai contenuti successivi"> Successivi &gt;</a>';
}

$document = file_get_contents('../HTML/GestioneContenuti.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='artworksNumberFoundPlaceholder'/>", $artworks_number_found, $document);
$document = str_replace("<span id='eventsNumberFoundPlaceholder'/>", $events_number_found, $document);
$document = str_replace("<span id='contentsListPlaceholder'/>", $contents_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_contents, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_contents, $document);

echo $document;

?>
