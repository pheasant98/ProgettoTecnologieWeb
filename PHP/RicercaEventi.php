<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_GET['search'])) {
    header('Location: Errore.php');
}

require_once ('Controller/EventsController.php');
$controller = new EventsController();
$event_count = $controller->getSearchedEventsCount($_GET['search']);

if($event_count === 1) {
    $event_number_found = 'ed è stato trovato ' . $event_count . ' risultato';
} else {
    $event_number_found = 'e sono stati trovati ' . $event_count . ' risultati';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($event_count / 5))) {
    header('Location: Errore.php');
} else {
    $page = $_GET['page'];
}

$offset = ($page - 1) * 5;

$event_list = $controller->getSearchedEvents($_GET['search'], $offset);

unset($controller);

$previous_events = '';
$next_events = '';

if ($page > 1) {
    $previous_events = '<a id="buttonBack" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page - 1) . '" title="Eventi precedenti" role="button" aria-label="Torna agli eventi precedenti"> &lt; Precedente</a>';
}

if (($page * 5) < $event_count) {
    $next_events = '<a id="buttonNext" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page + 1) . '" title="Eventi successivi" role="button" aria-label="Vai agli eventi successivi"> Successivo &gt;</a>';
}

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/RicercaEventi.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='resultNumberFoundPlaceholder'/>", $event_number_found, $document);
$document = str_replace("<span id='resultListPlaceholder'/>", $event_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_events, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_events, $document);

echo $document;

?>
