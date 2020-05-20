<?php

# TODO: decidere alt immagini: immagine dell'opera "titolo" appartenente alla categoria "tipologia"
# TODO: capire come compattare opere.php ed eventi.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once ('Controller/ArtworksController.php');
$controller = new ArtworksController();
$content_count = $controller->getArtworksCount();

$filter_type = '';

if (isset($_GET['filterType'])) {
    $filter_type = $_GET['filterType'];

    if ($_GET['filterType'] === 'Dipinti') {
        $content_count = $controller->getArtworksCountByStyle($filter_type);
    } elseif ($_GET['filterType'] === 'Sculture') {
        $content_count = $controller->getArtworksCountByStyle($filter_type);
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

$content_list = '<dl class="clickableList">' . $controller->getArtworks($filter_type, $offset) . '</dl>';
$content_number_found = '<p> Sono state trovate ' . $content_count . ' opere: </p>';

unset($controller);

$previous_content = '';
$next_content = '';

if ($page > 1) {
    $previous_content = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '&amp;filterType='. $filter_type . '" title="Opere precedenti" role="button" aria-label="Torna alle opere precedenti"> &lt; Precedente</a>';
}

if (($page * 5) < $content_count) {
    $next_content = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '&amp;filterType='. $filter_type . '" title="Opere successive" role="button" aria-label="Vai alle opere successive"> Successivo &gt;</a>';
}

$filter_select = '<form id="operaFilter" action="Opere.php" method="get">
                    <fieldset class="navigation">
                        <legend class="hideLegend">Filtro</legend>
                        
                        <label id="labelFilterType" for="filterType">Visualizza</label>
                        <select name="filterType" id="filterType" aria-label="filtro per lo stile dell\'opera">
                            <option value="TutteLeOpere" ' . ($filter_type == '' ? 'selected="selected"' : '') . '> Tutte le opere </option>
                            <option value="Dipinti" ' . ($filter_type == 'Dipinti' ? 'selected="selected"' : '') . '> Dipinti </option>
                            <option value="Sculture" ' . ($filter_type == 'Sculture' ? 'selected="selected"' : '') . '> Sculture </option>
                        </select>
        
                        <input id="buttonConfirm" class="button" value="Filtra" type="submit" aria-label="Filtra"/>
                    </fieldset>
                </form>';

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/Opere.html');
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
