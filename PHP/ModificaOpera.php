<?php

require_once ('Controller/LoginController.php');
require_once ('Controller/ArtworksController.php');

session_start();

if (!LoginController::isAuthenticatedUser() || !isset($_GET['id'])) {
    header('Location: Errore.php');
}

$message = '';
$artworksController = new ArtworksController();
$artwork = $artworksController->getArtwork($_GET['id']);

$author = $artwork['Autore'];
$title = $artwork['Titolo'];
$description = $artwork['Descrizione'];
$years = $artwork['Datazione'];
$style = $artwork['Stile'];
$technique = $artwork['Tecnica'];
$material = $artwork['Materiale'];
$dimensions = $artwork['Dimensioni'];
$loan = ($artwork['Prestito'] === 1 ? 'Si' : 'No');
$image = $artwork['Immagine'];


if (isset($_POST['submit']) && $_POST['submit'] === 'Modifica') {
    $author = $_POST['author'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $years = $_POST['years'];
    $style = $_POST['style'];
    //TODO: sistemare visualizzazione sulla base dello style in javascript
    if (isset($_POST['technique'])) {
        $technique = $_POST['technique'];
        $material = '';
    }
    if (isset($_POST['material'])) {
        $material = $_POST['material'];
        $technique = '';
    }
    $loan = ($_POST['loan'] === 'Si' ? 1 : 0);
    $_SESSION['artwork_title'] = $_POST['title'];
    $message = $artworksController->updateArtwork($_GET['id'], $author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan, $_SESSION['username']);
    unset($artworksController);
    if($message === '') {
        header('Location: OperaModificata.php');
    }
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

if ($style === 'Dipinti') {
    $painting_style = ' selected="selected" ';
} else {
    $sculture_style = ' selected="selected" ';
}

$document = file_get_contents('../HTML/ModificaOpera.html');
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
$document = str_replace("<span id='artworkImgPlaceholder'/>", $image, $document);

echo $document;

?>