<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Controller/LoginController.php');
require_once ('Controller/ArtworksController.php');

session_start();

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$message = '';

if (isset($_POST['submit']) && $_POST['submit'] === 'Inserisci') {
    $author = $_POST['author'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $years = $_POST['years'];
    $style = $_POST['style'];
    $technique = $_POST['technique'];
    $material = $_POST['material'];
    $dimensions = $_POST['dimensions'];
    $loan = ($_POST['loan'] === 'Si' ? 1 : 0);
    $artworksController = new ArtworksController();
    $message = $artworksController->addArtwork($author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan, $_SESSION['username']);
    unset($artworksController);
}

if ($message === '') {
    $author = '';
    $title = '';
    $description = '';
    $years = '';
    $style = 'Dipinto';
    $technique = '';
    $material = '';
    $dimensions = '';
    $loan = 'No';
}

$loan_yes = ' ';
$loan_no = ' ';
$painting_style = ' ';
$sculture_style = ' ';

if ($loan === 'No') {
    $loan_no = ' checked="checked" ';
} else {
    $loan_yes = ' checked="checked" ';
}

if ($style === 'Dipinto') {
    $painting_style = ' selected="selected" ';
} else {
    $sculture_style = ' selected="selected" ';
}

$document = file_get_contents('../HTML/InserisciOpera.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);
$document = str_replace("<span id='authorValuePlaceholder'/>", $author, $document);
$document = str_replace("<span id='titleValuePlaceholder'/>", $title, $document);
$document = str_replace("<span id='descriptionValuePlaceholder'/>", $description, $document);
$document = str_replace("<span id='yearsValuePlaceholder'/>", $years, $document);
$document = str_replace("<span id='paintingStyleSelectedPlaceholder'/>", $painting_style, $document);
$document = str_replace("<span id='scultureStyleSelectedPlaceholder'/>", $sculture_style, $document);
$document = str_replace("<span id='techniqueValuePlaceholder'/>", $technique, $document);
$document = str_replace("<span id='materialValuePlaceholder'/>", $material, $document);
$document = str_replace("<span id='dimensionsValuePlaceholder'/>", $dimensions, $document);
$document = str_replace("<span id='loanYesCheckedPlaceholder'/>", $loan_yes, $document);
$document = str_replace("<span id='loanNoCheckedPlaceholder'/>", $loan_no, $document);

echo $document;

?>