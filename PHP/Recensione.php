<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$review_title = $review['Titolo'];
$review_description = $review['Descrizione'];

$document = str_replace("<span id='titlePlaceholder'/>", $review_title, $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='descriptionPlaceholder'/>", $review_description, $document);

echo $document;

?>
