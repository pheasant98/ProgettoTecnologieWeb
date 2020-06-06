<?php

session_start();

require_once ('Controller/LoginController.php');
require_once ('Controller/ReviewsController.php');

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$controller = new ReviewsController();
if (!LoginController::isAdminUser()) {
    $reviews_count = $controller->getUserReviewsCount($_SESSION['username']);
} else {
    $reviews_count = $controller->getReviewsCount();
}

$deleted = '';
if (isset($_SESSION['reviewDeleted']) && isset($_SESSION['reviewDeletedError'])) {
    if ($_SESSION['reviewDeletedError']) {
        $deleted = 'La recensione ' . $_SESSION['reviewDeleted'] . ' è stata eliminata correttamente';
    } else {
        $deleted = 'Non è stato possibile eliminare la recensione' . $_SESSION['reviewDeleted'] . ', se l\'errore persiste si prega di segnalarlo al supporto tecnico.';
    }

    unset($_SESSION['reviewDeleted']);
    unset($_SESSION['reviewDeletedError']);
}

if($reviews_count == 1) {
    $user_reviews_count_found = '<p> È stata trovata ' . $reviews_count . ' recensione: </p>';
} else {
    $user_reviews_count_found = '<p> Sono state trovate ' . $reviews_count . ' recensioni: </p>';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($reviews_count / 5))) {
    header('Location: Errore.php');
} else {
    $page = $_GET['page'];
}
$description = '';
$title = '';
if ($reviews_count > 0) {
    $_SESSION['reviewPage'] = $page;
    $offset = ($page - 1) * 5;
    if (!LoginController::isAdminUser()) {
        $reviews_list = '<ul class="clickableList">' . $controller->getUserListReviews($_SESSION['username'], $offset) . '</ul>';
        $description = 'Elenco di tutte le recensioni lasciate';
        $title = 'Recensioni effettuate';
    } else {
        $reviews_list = '<ul class="clickableList">' . $controller->getListReviews($offset) . '</ul>';
        $description = 'Elenco di tutte le recensioni presenti all\'interno del sito';
        $title = 'Recensioni presenti nel sito';
    }

    unset($controller);

    $navigation_reviews_buttons = '<p class="navigation">';
    if ($page > 1) {
        $navigation_reviews_buttons .= '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '" title="Recensioni precedenti" role="button" aria-label="Torna alle recensioni precedenti"> &lt; Precedenti</a>';
    }

    if (($page * 5) < $reviews_count) {
        $navigation_reviews_buttons .= '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '" title="Recensioni successive" role="button" aria-label="Vai alle recensioni successive"> Successive &gt;</a>';
    }
    $navigation_reviews_buttons .= '</p>';

    $skip_reviews = '<a href="#buttonBack" class="skipInformation">Salta le recensioni presenti nella pagina</a>';
} else {
    unset($controller);
    $skip_reviews = '';
    $reviews_list = '';
    $navigation_reviews_buttons = '';
}

$_SESSION['previous_page'] = 'GestioneRecensioni';

$document = file_get_contents('../HTML/GestioneRecensioni.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='deletedContent'/>", $deleted, $document);
$document = str_replace("<span id='pageDescriptionPlaceholder'/>", $description, $document);
$document = str_replace("<span id='titlePlaceholder'/>", $title, $document);
$document = str_replace("<span id='reviewsNumberPlaceholder'/>", $user_reviews_count_found, $document);
$document = str_replace("<span id='skipReviewsPlaceholder'/>", $skip_reviews, $document);
$document = str_replace("<span id='reviewsListPlaceholder'/>", $reviews_list, $document);
$document = str_replace("<span id='navigationReviewsButtonsPlaceholder'/>", $navigation_reviews_buttons, $document);

echo $document;

?>
