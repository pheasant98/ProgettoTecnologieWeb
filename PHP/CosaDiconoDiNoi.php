<?php

session_start();

require_once ('Controller/LoginController.php');
require_once ('Controller/ReviewsController.php');

$controller = new ReviewsController();
$reviews_count = $controller->getReviewsCount();

if($reviews_count == 1) {
    $reviews_count_found = '<p> È stata trovata ' . $reviews_count . ' recensione </p>';
} else {
    $reviews_count_found = '<p> Sono state trovate ' . $reviews_count . ' recensioni </p>';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($reviews_count / 5))) {
    header('Location: Errore.php');
} else {
    $page = intval($_GET['page']);
}

$_SESSION['previous_page'] = 'CosaDiconoDiNoi';
$_SESSION['reviews_page'] = $page;

$review_clause = '';
$leave_review = '';
if (!LoginController::isAuthenticatedUser()) {
    $review_clause = 'Per lasciare una recensione è necessario aver effettuato l\'accesso al sito con un <span xml:lang="en">account</span> utente.';
} else if (LoginController::isAdminUser()) {
    $review_clause = 'Non è possibile lasciare una recensione se è stato fatto l\'accesso con un <span xml:lang="en">account</span> amministratore.';
} else {
    $leave_review = '<a id="reviewButton" class="button" href="LasciaUnaRecensione.php" title="Pagina di scrittura di una recensione" role="button" aria-label="Vai alla pagina di scrittura della recensione">Lascia una recensione</a>';
}


if ($reviews_count > 0) {
    $number_pages = intval(ceil($reviews_count / 5));
    $offset = ($page - 1) * 5;
    if ($page === 1) {
        if ($number_pages === 1){
            $reviews_list = '<dl class="clickableList">' . $controller->getReviews($offset, 'buttonBackUp') . '</dl>';
        } else {
            $reviews_list = '<dl class="clickableList">' . $controller->getReviews($offset, 'buttonNext') . '</dl>';
        }
    } else {
        $reviews_list = '<dl class="clickableList">' . $controller->getReviews($offset, 'buttonBack') . '</dl>';
    }

    unset($controller);

    $navigation_reviews_buttons = '<p class="buttonPosition">';
    if ($page > 1) {
        $navigation_reviews_buttons .= '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '" title="Torna alle recensioni precedenti" role="button" aria-label="Torna alle recensioni precedenti"> &lt; Precedenti</a>';
    }

    if (($page * 5) < $reviews_count) {
        $navigation_reviews_buttons .= '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '" title="Vai alle recensioni successive" role="button" aria-label="Vai alle recensioni successive"> Successive &gt;</a>';
    }

    $navigation_reviews_buttons .= '</p>';

    if ($number_pages > 1) {
        if ($page === 1) {
            $skip_reviews = '<p class="skipDown">Ti trovi a pagina ' . $page . ' di ' . $number_pages . ':' . '</p> <a href="#buttonNext">vai ai pulsanti di navigazione</a>';
        } else {
            $skip_reviews = '<p class="skipDown">Ti trovi a pagina ' . $page . ' di ' . $number_pages . ':' . '</p> <a href="#buttonBack">vai ai pulsanti di navigazione</a>';
        }
    } else {
        $skip_reviews = '<p>Ti trovi a pagina ' . $page . ' di ' . $number_pages . '.';
    }

} else {
    unset($controller);
    $skip_reviews = '';
    $reviews_list = '';
    $navigation_reviews_buttons = '';
}

$document = file_get_contents('../HTML/CosaDiconoDiNoi.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='clausePlaceholder'/>", $review_clause, $document);
$document = str_replace("<span id='leaveReviewPlaceholder'/>", $leave_review, $document);
$document = str_replace("<span id='skipReviewsPlaceholder'/>", $skip_reviews, $document);
$document = str_replace("<span id='reviewsNumberPlaceholder'/>", $reviews_count_found, $document);
$document = str_replace("<span id='reviewsListPlaceholder'/>", $reviews_list, $document);
$document = str_replace("<span id='navigationReviewsButtonPlaceholder'/>", $navigation_reviews_buttons, $document);

echo $document;

?>
