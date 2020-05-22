<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once ('Controller/LoginController.php');
require_once ('Controller/ReviewsController.php');

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Error.php');
}

$controller = new ReviewsController();
$user_reviews_count = $controller->getUserReviewsCount($_SESSION['username']);

if($user_reviews_count == 1) {
    $user_reviews_count_found = '<p> È stata trovata ' . $user_reviews_count . ' recensione: </p>';
} else {
    $user_reviews_count_found = '<p> Sono state trovate ' . $user_reviews_count . ' recensioni: </p>';
}

if (!isset($_GET['page'])) {
$page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($user_reviews_count / 5))) {
header('Location: Error.php');
} else {
$page = $_GET['page'];
}

$offset = ($page - 1) * 5;
$user_reviews_list = '<dl class="clickableList">' . $controller->getUserReviews($_SESSION['username'], $offset) . '</dl>';

unset($controller);

$previous_reviews = '';
if ($page > 1) {
    $previous_reviews = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '" title="Recensioni precedenti" role="button" aria-label="Torna alle recensioni precedenti"> &lt; Precedenti</a>';
}

$next_reviews = '';
if (($page * 5) < $user_reviews_count) {
    $next_reviews = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '" title="Recensioni successive" role="button" aria-label="Vai alle recensioni successive"> Successive &gt;</a>';
}

$document = file_get_contents('../HTML/RecensioniUtente.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='userReviewsNumberPlaceholder'/>", $user_reviews_count_found, $document);
$document = str_replace("<span id='userReviewsListPlaceholder'/>", $user_reviews_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_reviews, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_reviews, $document);

echo $document;

?>
