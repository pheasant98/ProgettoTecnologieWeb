<?php

session_start();

if (!isset($_GET['id'])) {
    header('Location: Errore.php');
}

require_once('Controller/LoginController.php');
$document = file_get_contents('../HTML/Recensione.html');

require_once('Controller/ReviewsController.php');
$controller = new ReviewsController();

$login = LoginController::getAuthenticationMenu();

$review = $controller->getReview($_GET['id']);

unset($controller);


$review_object = $review['Oggetto'];
$review_content = $review['Contenuto'];
$review_user = $review['Utente'];
$review_last_data = $review['DataUltimaModifica'];

$document = str_replace("<span id='objectPlaceholder'/>", $review_object, $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='contentPlaceholder'/>", $review_content, $document);
$document = str_replace("<span id='userPlaceholder'/>", $review_user, $document);
$document = str_replace("<span id='lastDataPlaceholder'/>", $review_last_data, $document);

echo $document;

?>
