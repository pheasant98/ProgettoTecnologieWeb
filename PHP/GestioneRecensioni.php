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
if (isset($_SESSION['deleted'])) {
    $deleted = 'La recensione ' . $_SESSION['deleted'] . ' è stata eliminata correttamente';
    unset($_SESSION['deleted']);
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

$_SESSION['page'] = $page;

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

$previous_reviews = '';
if ($page > 1) {
    $previous_reviews = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '" title="Recensioni precedenti" role="button" aria-label="Torna alle recensioni precedenti"> &lt; Precedenti</a>';
}

$next_reviews = '';
if (($page * 5) < $reviews_count) {
    $next_reviews = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '" title="Recensioni successive" role="button" aria-label="Vai alle recensioni successive"> Successive &gt;</a>';
}

$document = file_get_contents('../HTML/GestioneRecensioni.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='deletedContent'/>", $deleted, $document);
$document = str_replace("<span id='pageDescriptionPlaceholder'/>", $description, $document);
$document = str_replace("<span id='titlePlaceholder'/>", $title, $document);
$document = str_replace("<span id='reviewsNumberPlaceholder'/>", $user_reviews_count_found, $document);
$document = str_replace("<span id='reviewsListPlaceholder'/>", $reviews_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_reviews, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_reviews, $document);

echo $document;

?>
