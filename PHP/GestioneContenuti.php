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
$artwork_count = 0;
$event_count = 0;

$filter_content = 'NessunFiltro';
$filter_content_type = 'NessunFiltro';
$filter_content_types = 'NessunFiltro'; //TODO: sistemare con enum giusto

if (isset($_GET['filterContent'])) {
    $filter_content = $_GET['filterContent'];
    if ($_GET['filterContent'] === 'Opera') {
        if (isset($_GET['filterContentType'])) {
            $filter_content_type = $_GET['filterContentType'];
            if ($_GET['filterContentType'] === 'Dipinto') {
                $filter_content_types = 'Dipinti';
                $artwork_count = $artworks_controller->getArtworksCountByStyle($filter_content_types);
                echo $artwork_count;
            } elseif ($_GET['filterContentType'] === 'Scultura') {
                $filter_content_types = 'Sculture';
                $artwork_count = $artworks_controller->getArtworksCountByStyle($filter_content_types);
            } else {
                $artwork_count = $artworks_controller->getArtworksCount();
            }
        }
    } elseif ($_GET['filterContent'] === 'Evento') {
        if (isset($_GET['filterContentType'])) {
            $filter_content_type = $_GET['filterContentType'];
            if ($_GET['filterContentType'] === 'Mostra') {
                $filter_content_types = 'Mostre';
                $event_count = $events_controller->getEventsCountByType($filter_content_types);
            } elseif ($_GET['filterContentType'] === 'Conferenza') {
                $filter_content_types = 'Conferenze';
                $event_count = $events_controller->getEventsCountByType($filter_content_types);
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
if (isset($_SESSION['deleted']) && isset($_SESSION['deleted_type'])) {
    if ($_SESSION['deleted_type'] === 'Opera') {
        $deleted = 'L\'opera ' . $_SESSION['artwork_title_deleted'] . ' è stata eliminata correttamente';
    } else if ($_SESSION['deleted_type'] === 'Evento') {
        $deleted = 'L\'evento ' . $_SESSION['event_title_deleted'] . ' è stato eliminato correttamente';
    }

    unset($_SESSION['deleted']);
    unset($_SESSION['deleted_type']);
}

if ($artwork_count == 1) {
    $artworks_number_found = '<p> È stata trovata ' . $artwork_count . ' opera. </p>';
} else {
    $artworks_number_found = '<p> Sono state trovate ' . $artwork_count . ' opere. </p>';
}
if ($event_count == 1) {
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

$_SESSION['page'] = $page;
$_SESSION['filter_content'] = $filter_content;
$_SESSION['filter_content_type'] = $filter_content_type;

$offset = ($page - 1) * 5;
if ($artwork_count > 0) {
    if ((($artwork_count - $offset) < 5) && (($artwork_count - $offset) > 0)) {
        if ($event_count > 0) {
            //Mi restano meno opere di quelle che può contenere una pagina ma ho eventi quindi li concateno
            $contents_list = $artworks_controller->getArtworksTitle($filter_content_types === 'NessunFiltro' ? '' : $filter_content_types, $offset);
            $events_offset = ($page * 5) - $artwork_count;
            $contents_list .= $events_controller->getEventsTitle($filter_content_types === 'NessunFiltro' ? '' : $filter_content_types, 0, $events_offset);
        } else {
            //Mi restano meno opere di quelle che può contenere una pagina ma non ho eventi quindi le metto e concludo
            $contents_list = $artworks_controller->getArtworksTitle($filter_content_types === 'NessunFiltro' ? '' : $filter_content_types, $offset);
        }
    } elseif ($offset >= $artwork_count) {
        //Ho avuto opere ma sono finite
        $events_offset = $offset - $artwork_count;
        $contents_list = $events_controller->getEventsTitle($filter_content_types === 'NessunFiltro' ? '' : $filter_content_types, $events_offset);
    } else {
        //Ho opere e me ne stanno di più di quelle permesse in una pagina
        $contents_list = $artworks_controller->getArtworksTitle($filter_content_types === 'NessunFiltro' ? '' : $filter_content_types, $offset);
    }
} else {
    //Non ho mai avuto opere ed ho solo eventi
    $contents_list = $events_controller->getEventsTitle($filter_content_types === 'NessunFiltro' ? '' : $filter_content_types, $offset);
}

unset($artworks_controller);
unset($events_controller);

$previous_contents = '';
$next_contents = '';

if ($page > 1) {
    $previous_contents = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '&amp;filterContent='. $filter_content . '&amp;filterContentType='. $filter_content_type . '" title="Utenti precedenti" role="button" aria-label="Torna ai contenuti precedenti"> &lt; Precedenti</a>';
}

if (($page * 5) < ($artwork_count + $event_count)) {
    $next_contents = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '&amp;filterContent='. $filter_content . '&amp;filterContentType='. $filter_content_type . '" title="Utenti successivi" role="button" aria-label="Vai ai contenuti successivi"> Successivi &gt;</a>';
}

$filter_option_whole = $filter_content == 'NessunFiltro' ? ' selected="selected"' : '';
$filter_option_artworks = $filter_content == 'Opera' ? ' selected="selected"' : '';
$filter_option_events = $filter_content == 'Evento' ? ' selected="selected"' : '';
$filter_option_whole_type = $filter_content_type == 'NessunFiltro' ? ' selected="selected"' : '';
$filter_option_paintings = $filter_content_type == 'Dipinto' ? ' selected="selected"' : '';
$filter_option_scultures = $filter_content_type == 'Scultura' ? ' selected="selected"' : '';
$filter_option_exhibitions = $filter_content_type == 'Mostre' ? ' selected="selected"' : '';
$filter_option_conferences = $filter_content_type == 'Conferenze' ? ' selected="selected"' : '';

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
$document = str_replace("<span id='deletedContent'/>", $deleted, $document);
$document = str_replace("<span id='artworksNumberFoundPlaceholder'/>", $artworks_number_found, $document);
$document = str_replace("<span id='eventsNumberFoundPlaceholder'/>", $events_number_found, $document);
$document = str_replace("<span id='contentsListPlaceholder'/>", $contents_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_contents, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_contents, $document);

echo $document;

?>
