<?php

session_start();

if (!isset($_GET['search'])) {
    header('Location: Errore.php');
}

require_once ('Controller/ArtworksController.php');
$controller = new ArtworksController();
if (strlen($_GET['search']) > 64) {
    $error_length = '<p class="error">Non è possibile inserire un testo di ricerca più lungo di 64 caratteri.</p>';
    $artwork_count = 0;
} else {
    $artwork_count = $controller->getSearchedArtworksCount($_GET['search']);
    if($artwork_count === 1) {
        $artwork_number_found = '<p>È stata eseguita una ricerca tra le opere presenti nel sito ed è stato trovato ' . $artwork_count . ' risultato:</p>';
    } else {
        $artwork_number_found = '<p>È stata eseguita una ricerca tra le opere presenti nel sito e sono stati trovati ' . $artwork_count . ' risultati:</p>';
    }
    $error_length = '';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($artwork_count / 5))) {
    header('Location: Errore.php');
} else {
    $page = intval($_GET['page']);
}

if ($artwork_count > 0) {
    $offset = ($page - 1) * 5;

    $artwork_list = '<dl class="clickableList">' . $controller->getSearchedArtworks($_GET['search'], $offset) . '</dl>';

    unset($controller);

    $navigation_artworks_buttons = '<p class="buttonPosition">';

    if ($page > 1) {
        $navigation_artworks_buttons .= '<a id="buttonBack" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page - 1) . '" title="Opere precedenti" role="button" aria-label="Torna alle opere precedenti"> &lt; Precedenti</a>';
    }

    if (($page * 5) < $artwork_count) {
        $navigation_artworks_buttons .= '<a id="buttonNext" class="button" href="?search=' . $_GET['search'] . '&amp;page=' . ($page + 1) . '" title="Opere successive" role="button" aria-label="Vai alle opere successive"> Successive &gt;</a>';
    }

    $navigation_artworks_buttons .= '</p>';

    if ($page === 1) {
        $skip_artworks = '<p>Ti trovi a pagina ' . $page . ' di ' . (intval($artwork_count/5)+1) . ': ' . '<a href="#buttonNext">vai ai pulsanti di navigazione</a></p>';
    } else {
        $skip_artworks = '<p>Ti trovi a pagina ' . $page . ' di ' . (intval($artwork_count/5)+1) . ': ' . '<a href="#buttonBack">vai ai pulsanti di navigazione</a></p>';
    }

} else {
    unset($controller);
    $skip_artworks = '';
    $artwork_list = '';
    $navigation_artworks_buttons = '';
}

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/RicercaOpere.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);

if ($error_length === '') {
    $document = str_replace("<span id='resultNumberFoundPlaceholder'/>", $artwork_number_found, $document);
} else {
    $document = str_replace("<span id='resultNumberFoundPlaceholder'/>", $error_length, $document);
}

$document = str_replace("<span id='skipArtworksPlaceholder'/>", $skip_artworks, $document);
$document = str_replace("<span id='resultListPlaceholder'/>", $artwork_list, $document);
$document = str_replace("<span id='navigationArtworksButtonsPlaceholder'/>", $navigation_artworks_buttons, $document);

echo $document;

?>
