<?php

session_start();

require_once ('Controller/EventsController.php');
$controller = new EventsController();
$event_count = $controller->getEventsCount();

$filter_type = '';

if (isset($_GET['filterType'])) {
    $filter_type = $_GET['filterType'];

    if ($_GET['filterType'] === 'Mostra') {
        $event_count = $controller->getEventsCountByType($filter_type);
    } elseif ($_GET['filterType'] === 'Conferenza') {
        $event_count = $controller->getEventsCountByType($filter_type);
    } else {
        $filter_type = '';
    }
}

if($event_count == 1) {
    $event_number_found = '<p> È stato trovato ' . $event_count . ' evento </p>';
} else {
    $event_number_found = '<p> Sono stati trovati ' . $event_count . ' eventi </p>';
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

    $event_list = '<dl class="clickableList">' . $controller->getEvents($filter_type, $offset) . '</dl>';

    unset($controller);

    $navigation_events_buttons = '<p class="buttonPosition">';

    if ($page > 1) {
        $navigation_events_buttons .= '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '&amp;filterType='. $filter_type . '" title="Eventi precedenti" role="button" aria-label="Torna agli eventi precedenti"> &lt; Precedente</a>';
    }

    if (($page * 5) < $event_count) {
        $navigation_events_buttons .= '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '&amp;filterType='. $filter_type . '" title="Eventi successivi" role="button" aria-label="Vai agli eventi successivi"> Successivo &gt;</a>';
    }

    $navigation_events_buttons .= '</p>';

    $skip_events = '<a href="#buttonBack" class="skipInformation">Salta gli eventi presenti nella pagina</a>';
} else {
    unset($controller);
    $skip_events = '';
    $event_list = '';
    $navigation_events_buttons = '';
}

$_SESSION['previous_page'] = 'Eventi';
$_SESSION['filter_event_type'] = $filter_type;
$_SESSION['event_page'] = $page;

$filter_option_whole = $filter_type == '' ? ' selected="selected"' : '';
$filter_option_exhibitions = $filter_type == 'Mostra' ? ' selected="selected"' : '';
$filter_option_conferences = $filter_type == 'Conferenza' ? ' selected="selected"' : '';

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/Eventi.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='eventNumberFoundPlaceholder'/>", $event_number_found, $document);
$document = str_replace("<span id='skipEventsPlaceholder'/>", $skip_events, $document);
$document = str_replace("<span id='filterOptionWholePlaceholder'/>", $filter_option_whole, $document);
$document = str_replace("<span id='filterOptionExhibitionsPlaceholder'/>", $filter_option_exhibitions, $document);
$document = str_replace("<span id='filterOptionConferencesPlaceholder'/>", $filter_option_conferences, $document);
$document = str_replace("<span id='eventListPlaceholder'/>", $event_list, $document);
$document = str_replace("<span id='navigationEventsButtonsPlaceholder'/>", $navigation_events_buttons, $document);

echo $document;

?>
