<?php

# TODO: decidere alt immagini: immagine dell'opera "titolo" appartenente alla categoria "tipologia"
# TODO: capire come compattare opere.php ed eventi.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['type']) || (($_GET['type'] !== 'Opere') && ($_GET['type'] !== 'Eventi'))) {
    header('Location: Error.php');
}

if ($_GET['type'] === 'Opere') {
    require_once ('Controller/ArtworksController.php');
    $controller = new ArtworksController();
    $content_count = $controller->getArtworksCount();
} else {
    require_once ('Controller/EventsController.php');
    $controller = new EventsController();
    $content_count = $controller->getEventsCount();
}

$filter_type = '';
$link_filter_type = '&amp;type=' . $_GET['type'];

if (isset($_GET['filterType'])) {
    $filter_type = $_GET['filterType'];
    $link_filter_type .= '&amp;filterType=' . $filter_type;

    if ($_GET['filterType'] === 'Dipinti') {
        $content_count = $controller->getArtworksCountByStyle($filter_type);
    } elseif ($_GET['filterType'] === 'Sculture') {
        $content_count = $controller->getArtworksCountByStyle($filter_type);
    } elseif ($_GET['filterType'] === 'Mostre') {
        $content_count = $controller->getEventsCountByType($filter_type);
    } elseif ($_GET['filterType'] === 'Conferenze') {
        $content_count = $controller->getEventsCountByType($filter_type);
    } else {
        $filter_type = '';
    }
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($content_count / 5))) {
    header('Location: Error.php');
} else {
    $page = $_GET['page'];
}

$offset = ($page - 1) * 5;

if($_GET['type'] === 'Opere') {
    $content_list = '<dl class="clickableList">' . $controller->getArtworks($filter_type, $offset) . '</dl>';
    $content_number_found = '<p> Sono state trovate ' . $content_count . ' opere: </p>';
} else {
    $content_list = '<dl class="clickableList">' . $controller->getEvents($filter_type, $offset) . '</dl>';
    $content_number_found = '<p> Sono stati trovati ' . $content_count . ' eventi: </p>';
}

unset($controller);

$previous_content = '';
$next_content = '';

if ($page > 1) {
    if ($_GET['type'] === 'Opere') {
        $previous_content = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . $link_filter_type . '" title="Opere precedenti" role="button" aria-label="Torna alle opere precedenti"> &lt; Precedente</a>';
    } else {
        $previous_content = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . $link_filter_type . '" title="Eventi precedenti" role="button" aria-label="Torna agli eventi precedenti"> &lt; Precedente</a>';
    }
}

if (($page * 5) < $content_count) {
    if ($_GET['type'] === 'Opere') {
        $next_content = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . $link_filter_type . '" title="Opere successive" role="button" aria-label="Vai alle opere successive"> Successivo &gt;</a>';
    } else {
        $next_content = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . $link_filter_type . '" title="Eventi successivi" role="button" aria-label="Vai agli eventi successive"> Successivo &gt;</a>';
    }
}

if ($_GET['type'] === 'Opere') {
    $filter_select = '<form id="operaFilter" action="Contenuti.php" method="get">
                        <fieldset class="navigation">
                            <legend class="hideLegend">Filtro</legend>
                            
                            <input type="hidden" name="type" value="' . $_GET['type'] . '"/>
                            
                            <label id="labelFilterType" for="filterType">Visualizza</label>
                            <select name="filterType" id="filterType" aria-label="filtro per lo stile dell\'opera">
                                <option value="TutteLeOpere" ' . ($filter_type == '' ? 'selected="selected"' : '') . '> Tutte le opere </option>
                                <option value="Dipinti" ' . ($filter_type == 'Dipinti' ? 'selected="selected"' : '') . '> Dipinti </option>
                                <option value="Sculture" ' . ($filter_type == 'Sculture' ? 'selected="selected"' : '') . '> Sculture </option>
                            </select>
            
                            <input id="buttonConfirm" class="button" value="Filtra" type="submit" aria-label="Filtra"/>
                        </fieldset>
                    </form>';
} else {
    $filter_select = '<form id="eventFilter" action="Contenuti.php" method="get">
                            <fieldset class="navigation">
                                <legend class="hideLegend">Filtro</legend>
                                
                                <input type="hidden" name="type" value="' . $_GET['type'] . '"/>
                                
                                <label id="labelFilterType" for="filterType">Visualizza</label>
                                <select name="filterType" id="filterType" aria-label="filtro per la tipologia di evento">
                                    <option value="TuttiGliEventi" ' . ($filter_type == '' ? 'selected="selected"' : '') . '> Tutte gli eventi </option>
                                    <option value="Mostre" ' . ($filter_type == 'Mostre' ? 'selected="selected"' : '') . '> Mostre </option>
                                    <option value="Conferenze"' . ($filter_type == 'Conferenze' ? 'selected="selected"' : '') . '> Conferenze </option>
                                </select>
            
                                <input id="buttonConfirm" class="button" value="Filtra" type="submit" aria-label="Filtra"/>
                            </fieldset>
                        </form>';
}

session_start();

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/Contenuti.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='titlePlaceholder'/>", $_GET['type'], $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='contentNumberFound'/>", $content_number_found, $document);
$document = str_replace("<span id='filterSelectPlaceholder'/>", $filter_select, $document);
$document = str_replace("<span id='contentNumberPlaceholder'/>", $content_count, $document);
$document = str_replace("<span id='contentListPlaceholder'/>", $content_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_content, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_content, $document);

echo $document;

?>
