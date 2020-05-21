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

$artwork = $controller->getArtwork($_GET['id']);
$artwork_title = $artwork['Titolo'];
$artwork_img = $artwork['Immagine'];
$artwork_author = $artwork['Autore'];
$artwork_age = $artwork['Anni'];
$artwork_style = $artwork['Stile'];
$artwork_technique = $artwork['Tecnica'];
$artwork_material = $artwork['Materiale'];
$artwork_dimension = $artwork['Dimensioni'];
$artwork_loan = ($artwork['Prestito'] == 1 ? 'sì' : 'no');
$artwork_description = $artwork['Descrizione'];

$document = str_replace("<span id='titlePlaceholder'/>", $artwork_title, $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='artworkImgPlaceholder'/>", $artwork_img, $document);
$document = str_replace("<span id='artworkAuthorPlaceholder'/>", $artwork_author, $document);
$document = str_replace("<span id='artworkAgePlaceholder'/>", $artwork_age, $document);
$document = str_replace("<span id='artworkStylePlaceholder'/>", $artwork_style, $document);
$document = str_replace("<span id='artworkTechniquePlaceholder'/>", $artwork_technique, $document);
$document = str_replace("<span id='artworkMaterialPlaceholder'/>", $artwork_material, $document);
$document = str_replace("<span id='artworkDimensionPlaceholder'/>", $artwork_dimension, $document);
$document = str_replace("<span id='artworkLoanPlaceholder'/>", $artwork_loan, $document);
$document = str_replace("<span id='artworkDescriptionPlaceholder'/>", $artwork_description, $document);

echo $document;

?>
