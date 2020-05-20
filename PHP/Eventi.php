<?php

# TODO: decidere alt immagini: immagine dell'opera "titolo" appartenente alla categoria "tipologia"
# TODO: capire come compattare opere.php ed eventi.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once ('Controller/EventsController.php');
$controller = new EventsController();
$content_count = $controller->getEventsCount();

$filter_type = '';

if (isset($_GET['filterType'])) {
    $filter_type = $_GET['filterType'];

    if ($_GET['filterType'] === 'Mostre') {
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

$content_list = '<dl class="clickableList">' . $controller->getEvents($filter_type, $offset) . '</dl>';
$content_number_found = '<p> Sono stati trovati ' . $content_count . ' eventi: </p>';

unset($controller);

$previous_content = '';
$next_content = '';

if ($page > 1) {
    $previous_content = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '&amp;filterType='. $filter_type . '" title="Eventi precedenti" role="button" aria-label="Torna agli eventi precedenti"> &lt; Precedente</a>';
}

if (($page * 5) < $content_count) {
    $next_content = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '&amp;filterType='. $filter_type . '" title="Eventi successivi" role="button" aria-label="Vai agli eventi successivi"> Successivo &gt;</a>';
}

$filter_select = '<form id="eventFilter" action="Eventi.php" method="get">
                        <fieldset class="navigation">
                            <legend class="hideLegend">Filtro</legend>
                            
                            <label id="labelFilterType" for="filterType">Visualizza</label>
                            <select name="filterType" id="filterType" aria-label="filtro per la tipologia di evento">
                                <option value="TuttiGliEventi" ' . ($filter_type == '' ? 'selected="selected"' : '') . '> Tutti gli eventi </option>
                                <option value="Mostre" ' . ($filter_type == 'Mostre' ? 'selected="selected"' : '') . '> Mostre </option>
                                <option value="Conferenze"' . ($filter_type == 'Conferenze' ? 'selected="selected"' : '') . '> Conferenze </option>
                            </select>
        
                            <input id="buttonConfirm" class="button" value="Filtra" type="submit" aria-label="Filtra"/>
                        </fieldset>
                    </form>';

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/Eventi.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='titlePlaceholder'/>", "ciao", $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='contentNumberFound'/>", $content_number_found, $document);
$document = str_replace("<span id='filterSelectPlaceholder'/>", $filter_select, $document);
$document = str_replace("<span id='contentNumberPlaceholder'/>", $content_count, $document);
$document = str_replace("<span id='contentListPlaceholder'/>", $content_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_content, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_content, $document);

echo $document;

?>
