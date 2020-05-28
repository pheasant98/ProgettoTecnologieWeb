<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_GET['search'])) {
    header('Location: Errore.php');
}

require_once ('Controller/ArtworksController.php');
$controller = new ArtworksController();
$artwork_count = $controller->getSearchedArtworksCount($_GET['search']);

if($artwork_count === 1) {
    $artwork_number_found = 'ed è stato trovato ' . $artwork_count . ' risultato';
} else {
    $artwork_number_found = 'e sono stati trovati ' . $artwork_count . ' risultati';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($artwork_count / 5))) {
    header('Location: Errore.php');
} else {
    $page = $_GET['page'];
}

$offset = ($page - 1) * 5;

$artwork_list = '<dl class="clickableList">' . $controller->getSearchedArtworks($_GET['search'], $offset) . '</dl>';

unset($controller);

$previous_artworks = '';
$next_artworks = '';

if ($page > 1) {
    $previous_artworks = '<a id="buttonBack" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page - 1) . '" title="Opere precedenti" role="button" aria-label="Torna alle opere precedenti"> &lt; Precedenti</a>';
}

if (($page * 5) < $artwork_count) {
    $next_artworks = '<a id="buttonNext" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page + 1) . '" title="Opere successive" role="button" aria-label="Vai alle opere successive"> Successive &gt;</a>';
}

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/RicercaOpere.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='resultNumberPlaceholder'/>", $artwork_number_found, $document);
$document = str_replace("<span id='resultListPlaceholder'/>", $artwork_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_artworks, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_artworks, $document);

echo $document;

?>
