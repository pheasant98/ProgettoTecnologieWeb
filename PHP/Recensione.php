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

$review_title = $review['Oggetto'];
$review_description = $review['Contenuto'];

$document = str_replace("<span id='titlePlaceholder'/>", $review_title, $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='descriptionPlaceholder'/>", $review_description, $document);

echo $document;

?>
