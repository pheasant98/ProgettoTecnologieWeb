<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/Opera.html');

require_once ('Controller/ArtworksController.php');
$controller = new ArtworksController();

$login = LoginController::getAuthenticationMenu();

$single_opera = $controller->getArtwork($_GET['id']);

$document = str_replace("<span id='titlePlaceholder'/>", "ciao", $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='singleOperaPlaceholder'/>", $single_opera, $document);

echo $document;

?>
