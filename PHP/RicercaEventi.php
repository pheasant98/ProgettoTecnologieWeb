<?php

session_start();

if (!isset($_GET['search'])) {
    header('Location: Errore.php');
}

require_once ('Controller/EventsController.php');
$controller = new EventsController();
if (strlen($_GET['search']) > 64) {
    $error_length = '<p class="error">Non è possibile inserire un testo di ricerca più lungo di 64 caratteri.</p>';
    $event_count = 0;
} else {
    $event_count = $controller->getSearchedEventsCount($_GET['search']);
    if($event_count === 1) {
        $event_number_found = '<p>È stata eseguita una ricerca tra gli eventi presenti nel sito ed è stato trovato ' . $event_count . ' risultato:</p>';
    } else {
        $event_number_found = '<p>È stata eseguita una ricerca tra gli eventi presenti nel sito e sono stati trovati ' . $event_count . ' risultati:</p>';
    }
    $error_length = '';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($event_count / 5))) {
    header('Location: Errore.php');
} else {
    $page = intval($_GET['page']);
}

if ($event_count > 0) {
    $offset = ($page - 1) * 5;

    $number_pages = intval(ceil($event_count / 5));
    $offset = ($page - 1) * 5;
    if ($page === 1) {
        if ($number_pages === 1){
            $event_list = '<dl class="clickableList">' . $controller->getSearchedEvents($_GET['search'], $offset, 'buttonBackUp') . '</dl>';
        } else {
            $event_list = '<dl class="clickableList">' . $controller->getSearchedEvents($_GET['search'], $offset, 'buttonNext') . '</dl>';
        }
    } else {
        $event_list = '<dl class="clickableList">' . $controller->getSearchedEvents($_GET['search'], $offset, 'buttonBack') . '</dl>';
    }

    unset($controller);

    $navigation_events_buttons = '<p class="buttonPosition">';

    if ($page > 1) {
        $navigation_events_buttons .= '<a id="buttonBack" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page - 1) . '" title="Eventi precedenti" role="button" aria-label="Torna agli eventi precedenti"> &lt; Precedente</a>';
    }

    if (($page * 5) < $event_count) {
        $navigation_events_buttons .= '<a id="buttonNext" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page + 1) . '" title="Eventi successivi" role="button" aria-label="Vai agli eventi successivi"> Successivo &gt;</a>';
    }

    $navigation_events_buttons .= '</p>';

    $number_pages = ceil($event_count/5);
    if ($number_pages > 1) {
        if ($page === 1) {
            $skip_events = '<p>Ti trovi a pagina ' . $page . ' di ' . $number_pages . ': ' . '<a href="#buttonNext">vai ai pulsanti di navigazione</a></p>';
        } else {
            $skip_events = '<p>Ti trovi a pagina ' . $page . ' di ' . $number_pages . ': ' . '<a href="#buttonBack">vai ai pulsanti di navigazione</a></p>';
        }
    } else {
        $skip_events = '<p>Ti trovi a pagina ' . $page . ' di ' . $number_pages . '.';
    }


} else {
    unset($controller);
    $skip_events = '';
    $event_list = '';
    $navigation_events_buttons = '';
}

$_SESSION['previous_page'] = 'RicercaEventi';
$_SESSION['search_event_string'] = $_GET['search'];
$_SESSION['search_event_page'] = $page;

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/RicercaEventi.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);

if ($error_length === '') {
    $document = str_replace("<span id='resultNumberFoundPlaceholder'/>", $event_number_found, $document);
} else {
    $document = str_replace("<span id='resultNumberFoundPlaceholder'/>", $error_length, $document);
}

$document = str_replace("<span id='skipEventsPlaceholder'/>", $skip_events, $document);
$document = str_replace("<span id='searchTextPlaceholder'/>", $_SESSION['search_event_string'], $document);
$document = str_replace("<span id='resultListPlaceholder'/>", $event_list, $document);
$document = str_replace("<span id='navigationEventsButtonsPlaceholder'/>", $navigation_events_buttons, $document);

echo $document;

?>
