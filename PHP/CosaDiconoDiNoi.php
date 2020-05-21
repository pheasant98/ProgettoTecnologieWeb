<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once ('Controller/LoginController.php');
require_once ('Controller/ReviewsController.php');

$controller = new ReviewsController();
$reviews_count = $controller->getReviewsCount();

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($reviews_count / 5))) {
    header('Location: Error.php');
} else {
    $page = $_GET['page'];
}

$leave_review = '';
if (LoginController::isAuthenticatedUser()) {
    $leave_review = '<a id="reviewButton" class="button" href="LasciaUnaRecensione.php" title="Pagina di scrittura di una recensione" role="button" aria-label="Vai alla pagina di scrittura della recensione">Lascia una recensione</a>';
}

$offset = ($page - 1) * 5;
$reviews_list = '<dl class="clickableList">' . $controller->getReviews($offset) . '</dl>';

unset($controller);

$previous_reviews = '';
if ($page > 1) {
    $previous_reviews = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '" title="Recensioni precedenti" role="button" aria-label="Torna alle recensioni precedenti"> &lt; Precedenti</a>';
}

$next_reviews = '';
if (($page * 5) < $reviews_count) {
    $next_reviews = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '" title="Recensioni successive" role="button" aria-label="Vai alle recensioni successive"> Successive &gt;</a>';
}

$document = file_get_contents('../HTML/CosaDiconoDiNoi.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='leaveReviewPlaceholder'/>", $leave_review, $document);
$document = str_replace("<span id='reviewsNumberPlaceholder'/>", $reviews_count, $document);
$document = str_replace("<span id='reviewsListPlaceholder'/>", $reviews_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_reviews, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_reviews, $document);

echo $document;

?>
