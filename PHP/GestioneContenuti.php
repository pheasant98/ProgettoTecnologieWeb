<?php

require_once ('Controller/ArtworksController.php');
require_once ('Controller/EventsController.php');
require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser() || !LoginController::isAdminUser()) {
    header('Location: Errore.php');
}

$artworks_controller = new ArtworksController();
$events_controller = new EventsController();
$artwork_count = 0;
$event_count = 0;

$filter_content = 'NessunFiltro';
$filter_content_type = 'NessunFiltro';

if (isset($_GET['filterContent'])) {
    $filter_content = $_GET['filterContent'];
    if ($_GET['filterContent'] === 'Opera') {
        if (isset($_GET['filterContentType'])) {
            $filter_content_type = $_GET['filterContentType'];
            if ($_GET['filterContentType'] === 'Dipinto') {
                $artwork_count = $artworks_controller->getArtworksCountByStyle($filter_content_type);
            } elseif ($_GET['filterContentType'] === 'Scultura') {
                $artwork_count = $artworks_controller->getArtworksCountByStyle($filter_content_type);
            } else {
                $artwork_count = $artworks_controller->getArtworksCount();
            }
        }
    } elseif ($_GET['filterContent'] === 'Evento') {
        if (isset($_GET['filterContentType'])) {
            $filter_content_type = $_GET['filterContentType'];
            if ($_GET['filterContentType'] === 'Mostra') {
                $event_count = $events_controller->getEventsCountByType($filter_content_type);
            } elseif ($_GET['filterContentType'] === 'Conferenza') {
                $event_count = $events_controller->getEventsCountByType($filter_content_type);
            } else {
                $event_count = $events_controller->getEventsCount();
            }
        }
    } else {
        $artwork_count = $artworks_controller->getArtworksCount();
        $event_count = $events_controller->getEventsCount();
    }
} else {
    $artwork_count = $artworks_controller->getArtworksCount();
    $event_count = $events_controller->getEventsCount();
}

$deleted = '';
if (isset($_SESSION['contentDeleted']) && isset($_SESSION['deleted_type']) && isset($_SESSION['contentDeletedError'])) {
    if ($_SESSION['deleted_type'] === 'Opera') {
        if ($_SESSION['contentDeletedError']) {
            $deleted = '<p class="success">L\'opera ' . $_SESSION['contentDeleted'] . ' è stata eliminata correttamente</p>';
        } else {
            $deleted = '<p class="error">Non è stato possibile eliminare l\'opera ' . $_SESSION['contentDeleted'] . ', se l\'errore persiste si prega di segnalarlo al supporto tecnico.</p>';
        }
    } else if ($_SESSION['deleted_type'] === 'Evento') {
        if ($_SESSION['contentDeletedError']) {
            $deleted = '<p class="success">L\'evento ' . $_SESSION['contentDeleted'] . ' è stato eliminato correttamente</p>';
        } else {
            $deleted = '<p class="error">Non è stato possibile eliminare l\'evento ' . $_SESSION['contentDeleted'] . ', se l\'errore persiste si prega di segnalarlo al supporto tecnico.</p>';
        }
    }

    unset($_SESSION['contentDeleted']);
    unset($_SESSION['deleted_type']);
    unset($_SESSION['contentDeletedError']);
}

if ($filter_content === 'NessunFiltro') {
    if ($artwork_count === 1) {
        $artworks_number_found = '<p> È stata trovata ' . $artwork_count . ' opera </p>';
    } else {
        $artworks_number_found = '<p> Sono state trovate ' . $artwork_count . ' opere </p>';
    }
    if ($event_count === 1) {
        $events_number_found = '<p> È stato trovato ' . $event_count . ' evento </p>';
    } else {
        $events_number_found = '<p> Sono stati trovati ' . $event_count . ' eventi </p>';
    }
} elseif ($filter_content === 'Opera') {
    if ($artwork_count === 1) {
        $artworks_number_found = '<p> È stata trovata ' . $artwork_count . ' opera </p>';
    } else {
        $artworks_number_found = '<p> Sono state trovate ' . $artwork_count . ' opere </p>';
    }
    $events_number_found = '';
} else {
    if ($event_count === 1) {
        $events_number_found = '<p> È stato trovato ' . $event_count . ' evento </p>';
    } else {
        $events_number_found = '<p> Sono stati trovati ' . $event_count . ' eventi </p>';
    }
    $artworks_number_found = '';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > (($artwork_count + $event_count) / 5))) {
    header('Location: Errore.php');
} else {
    $page = intval($_GET['page']);
}

if (($artwork_count + $event_count) > 0) {

    $_SESSION['contentPage'] = $page;
    $_SESSION['filter_content'] = $filter_content;
    $_SESSION['filter_content_type'] = $filter_content_type;

    $contents_list = '<ul class="separation">';
    $offset = ($page - 1) * 5;
    if ($artwork_count > 0) {
        if ((($artwork_count - $offset) < 5) && (($artwork_count - $offset) > 0)) {
            if ($event_count > 0) {
                //Mi restano meno opere di quelle che può contenere una pagina ma ho eventi quindi li concateno
                $contents_list .= $artworks_controller->getArtworksTitle($filter_content_type === 'NessunFiltro' ? '' : $filter_content_type, $offset);
                $events_offset = ($page * 5) - $artwork_count;
                $contents_list .= $events_controller->getEventsTitle($filter_content_type === 'NessunFiltro' ? '' : $filter_content_type, 0, $events_offset);
            } else {
                //Mi restano meno opere di quelle che può contenere una pagina ma non ho eventi quindi le metto e concludo
                $contents_list .= $artworks_controller->getArtworksTitle($filter_content_type === 'NessunFiltro' ? '' : $filter_content_type, $offset);
            }
        } elseif ($offset >= $artwork_count) {
            //Ho avuto opere ma sono finite
            $events_offset = $offset - $artwork_count;
            $contents_list .= $events_controller->getEventsTitle($filter_content_type === 'NessunFiltro' ? '' : $filter_content_type, $events_offset);
        } else {
            //Ho opere e me ne stanno di più di quelle permesse in una pagina
            $contents_list .= $artworks_controller->getArtworksTitle($filter_content_type === 'NessunFiltro' ? '' : $filter_content_type, $offset);
        }
    } else {
        //Non ho mai avuto opere ed ho solo eventi
        $contents_list .= $events_controller->getEventsTitle($filter_content_type === 'NessunFiltro' ? '' : $filter_content_type, $offset);
    }

    $contents_list .= '</ul>';

    unset($artworks_controller);
    unset($events_controller);

    $navigation_contents_buttons = '<p class="buttonPosition">';

    if ($page > 1) {
        $navigation_contents_buttons .= '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '&amp;filterContent=' . $filter_content . '&amp;filterContentType=' . $filter_content_type . '" title="Utenti precedenti" role="button" aria-label="Torna ai contenuti precedenti"> &lt; Precedenti</a>';
    }

    if (($page * 5) < ($artwork_count + $event_count)) {
        $navigation_contents_buttons .= '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '&amp;filterContent=' . $filter_content . '&amp;filterContentType=' . $filter_content_type . '" title="Utenti successivi" role="button" aria-label="Vai ai contenuti successivi"> Successivi &gt;</a>';
    }
    $navigation_contents_buttons .= '</p>';

    if ($page === 1) {
        $skip_contents = '<p>Ti trovi a pagina ' . $page . ' di ' . (intval(($artwork_count + $event_count)/5)+1) . ': ' . '<a href="#buttonNext">vai ai pulsanti di navigazione</a></p>';
    } else {
        $skip_contents = '<p>Ti trovi a pagina ' . $page . ' di ' . (intval(($artwork_count + $event_count)/5)+1) . ': ' . '<a href="#buttonBack">vai ai pulsanti di navigazione</a></p>';
    }

} else {
    unset($artworks_controller);
    unset($events_controller);
    $skip_contents = '';
    $contents_list = '';
    $navigation_contents_buttons = '';
    $_SESSION['filter_content'] = 'NessunFiltro';
    $_SESSION['filter_content_type'] = 'NessunFiltro';
}

$_SESSION['previous_page'] = 'GestioneContenuti';
$filter_option_whole = $filter_content === 'NessunFiltro' ? ' selected="selected"' : '';
$filter_option_artworks = $filter_content === 'Opera' ? ' selected="selected"' : '';
$filter_option_events = $filter_content === 'Evento' ? ' selected="selected"' : '';
$filter_option_whole_type = $filter_content_type === 'NessunFiltro' ? ' selected="selected"' : '';
$filter_option_paintings = $filter_content_type === 'Dipinto' ? ' selected="selected"' : '';
$filter_option_scultures = $filter_content_type === 'Scultura' ? ' selected="selected"' : '';
$filter_option_exhibitions = $filter_content_type === 'Mostra' ? ' selected="selected"' : '';
$filter_option_conferences = $filter_content_type === 'Conferenza' ? ' selected="selected"' : '';

$document = file_get_contents('../HTML/GestioneContenuti.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);

$document = str_replace("<span id='filterOptionWholePlaceholder'/>", $filter_option_whole, $document);
$document = str_replace("<span id='filterOptionArtworksPlaceholder'/>", $filter_option_artworks, $document);
$document = str_replace("<span id='filterOptionEventsPlaceholder'/>", $filter_option_events, $document);
$document = str_replace("<span id='filterOptionWholeTypePlaceholder'/>", $filter_option_whole_type, $document);
$document = str_replace("<span id='filterOptionPaintingsPlaceholder'/>", $filter_option_paintings, $document);
$document = str_replace("<span id='filterOptionSculturesPlaceholder'/>", $filter_option_scultures, $document);
$document = str_replace("<span id='filterOptionExhibitionsPlaceholder'/>", $filter_option_exhibitions, $document);
$document = str_replace("<span id='filterOptionConferencesPlaceholder'/>", $filter_option_conferences, $document);
$document = str_replace("<span id='deletedContentPlaceholder'/>", $deleted, $document);
$document = str_replace("<span id='artworksNumberFoundPlaceholder'/>", $artworks_number_found, $document);
$document = str_replace("<span id='eventsNumberFoundPlaceholder'/>", $events_number_found, $document);
$document = str_replace("<span id='skipContentsPlaceholder'/>", $skip_contents, $document);
$document = str_replace("<span id='contentsListPlaceholder'/>", $contents_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $navigation_contents_buttons, $document);

echo $document;

?>
