<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Controller/LoginController.php');
require_once ('Controller/ArtworksController.php');

session_start();

//if (!LoginController::isAuthenticatedUser()) {
  //  header('Location: Error.php');
//}

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
    $loan = $_POST['loan'];
    $image = $_POST['image'];

    $artworksController = new ArtworksController();
    $message = $artworksController->addArtwork($author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan, $image, $_SESSION['username']);
    unset($artworksController);

    if ($loan === 'No') {
        $loan_yes = '<input id="loanYes" name="loan" value="Si" type="radio" aria-label="Pulsante per dire di si al prestito"/>';
        $loan_no = '<input id="loanNo" name="loan" value="No" type="radio" checked="checked" aria-label="Pulsante per dire di no al prestito"/>';
    } else {
        $loan_yes = '<input id="loanYes" name="loan" value="Si" type="radio" checked="checked" aria-label="Pulsante per dire di si al prestito"/>';
        $loan_no = '<input id="loanNo" name="loan" value="No" type="radio" aria-label="Pulsante per dire di no al prestito"/>';
    }

    if ($style === 'Dipinto') {
        $painting_style = '<option selected="selected" value="Dipinto">Dipinto</option>';
        $sculture_style = '<option value="Scultura">Scultura</option>';
    } else {
        $painting_style = '<option value="Dipinto">Dipinto</option>';
        $sculture_style = '<option selected="selected" value="Scultura">Scultura</option>';
    }
}

if ($message === '') {
    $author = '';
    $title = '';
    $description = '';
    $years = '';
    $painting_style = '<option selected="selected" value="Dipinto">Dipinto</option>';
    $sculture_style = '<option value="Scultura">Scultura</option>';
    $technique = '';
    $material = '';
    $dimensions = '';
    $loan_yes = '<input id="loanYes" name="loan" value="Si" type="radio" aria-label="Pulsante per dire di si al prestito"/>';
    $loan_no = '<input id="loanNo" name="loan" value="No" type="radio" checked="checked" aria-label="Pulsante per dire di no al prestito"/>';
    //$image = $_POST['image'];
}

$document = file_get_contents('../HTML/InserisciOpera.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);
$document = str_replace("<span id='authorValuePlaceholder'/>", $author, $document);
$document = str_replace("<span id='titleValuePlaceholder'/>", $title, $document);
$document = str_replace("<span id='descriptionValuePlaceholder'/>", $description, $document);
$document = str_replace("<span id='yearsValuePlaceholder'/>", $years, $document);
$document = str_replace("<span id='paintingStyleValuePlaceholder'/>", $painting_style, $document);
$document = str_replace("<span id='scultureStyleValuePlaceholder'/>", $sculture_style, $document);
$document = str_replace("<span id='techniqueValuePlaceholder'/>", $technique, $document);
$document = str_replace("<span id='materialValuePlaceholder'/>", $material, $document);
$document = str_replace("<span id='dimensionsValuePlaceholder'/>", $dimensions, $document);
$document = str_replace("<span id='loanYesValuePlaceholder'/>", $loan_yes, $document);
$document = str_replace("<span id='loanNoValuePlaceholder'/>", $loan_no, $document);

echo $document;

?>