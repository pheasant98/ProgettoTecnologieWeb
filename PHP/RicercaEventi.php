<?php

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

if ($event_count > 0) {
    $offset = ($page - 1) * 5;

    $event_list = '<dl class="clickableList">' . $controller->getSearchedEvents($_GET['search'], $offset) . '</dl>';

    unset($controller);

    $navigation_events_buttons = '<p class="navigation">';

    if ($page > 1) {
        $navigation_events_buttons .= '<a id="buttonBack" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page - 1) . '" title="Eventi precedenti" role="button" aria-label="Torna agli eventi precedenti"> &lt; Precedente</a>';
    }

    if (($page * 5) < $event_count) {
        $navigation_events_buttons .= '<a id="buttonNext" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page + 1) . '" title="Eventi successivi" role="button" aria-label="Vai agli eventi successivi"> Successivo &gt;</a>';
    }

    $navigation_events_buttons .= '</p>';

    $skip_events = '<a href="#buttonBack" class="skipInformation">Salta i risultati presenti nella pagina</a>';
} else {
    unset($controller);
    $skip_events = '';
    $event_list = '';
    $navigation_events_buttons = '';
}

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/RicercaEventi.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='resultNumberFoundPlaceholder'/>", $event_number_found, $document);
$document = str_replace("<span id='skipEventsPlaceholder'/>", $skip_events, $document);
$document = str_replace("<span id='resultListPlaceholder'/>", $event_list, $document);
$document = str_replace("<span id='navigationEventsButtonsPlaceholder'/>", $navigation_events_buttons, $document);

echo $document;

?>
