<?php

session_start();

require_once ('Controller/ArtworksController.php');
$controller = new ArtworksController();
$artwork_count = $controller->getArtworksCount();

$filter_type = '';

if (isset($_GET['filterType'])) {
    $filter_type = $_GET['filterType'];

    if ($_GET['filterType'] === 'Dipinto') {
        $artwork_count = $controller->getArtworksCountByStyle($filter_type);
    } elseif ($_GET['filterType'] === 'Scultura') {
        $artwork_count = $controller->getArtworksCountByStyle($filter_type);
    } else {
        $filter_type = '';
    }
}

if($artwork_count == 1) {
    $artwork_number_found = '<p> È stata trovata ' . $artwork_count . ' opera: </p>';
} else {
    $artwork_number_found = '<p> Sono state trovate ' . $artwork_count . ' opere: </p>';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($artwork_count / 5))) {
    header('Location: Errore.php');
} else {
    $page = $_GET['page'];
}

if ($artwork_count > 0) {
    $offset = ($page - 1) * 5;

    $artwork_list = '<dl class="clickableList">' . $controller->getArtworks($filter_type, $offset) . '</dl>';

    unset($controller);

    $navigation_artworks_buttons = '<p class="navigation">';

    if ($page > 1) {
        $navigation_artworks_buttons .= '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '&amp;filterType='. $filter_type . '" title="Opere precedenti" role="button" aria-label="Torna alle opere precedenti"> &lt; Precedenti</a>';
    }

    if (($page * 5) < $artwork_count) {
        $navigation_artworks_buttons .= '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '&amp;filterType='. $filter_type . '" title="Opere successive" role="button" aria-label="Vai alle opere successive"> Successive &gt;</a>';
    }

    $navigation_artworks_buttons .= '</p>';

    $skip_artworks = '<a href="#buttonBack" class="skipInformation">Salta le opere presenti nella pagina</a>';
} else {
    unset($controller);
    $skip_artworks = '';
    $artwork_list = '';
    $navigation_artworks_buttons = '';
}

$_SESSION['previous_page'] = 'Opere';

$filter_option_whole = $filter_type == '' ? ' selected="selected"' : '';
$filter_option_paintings = $filter_type == 'Dipinto' ? ' selected="selected"' : '';
$filter_option_sculptures = $filter_type == 'Scultura' ? ' selected="selected"' : '';

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/Opere.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='artworkNumberFoundPlaceholder'/>", $artwork_number_found, $document);
$document = str_replace("<span id='skipArtworksPlaceholder'/>", $skip_artworks, $document);
$document = str_replace("<span id='filterOptionsWholePlaceholder'/>", $filter_option_whole, $document);
$document = str_replace("<span id='filterOptionPaintingsPlaceholder'/>", $filter_option_paintings, $document);
$document = str_replace("<span id='filterOptionSculpturesPlaceholder'/>", $filter_option_sculptures, $document);
$document = str_replace("<span id='artworkListPlaceholder'/>", $artwork_list, $document);
$document = str_replace("<span id='navigationArtworksButtonsPlaceholder'/>", $navigation_artworks_buttons, $document);

echo $document;

?>
