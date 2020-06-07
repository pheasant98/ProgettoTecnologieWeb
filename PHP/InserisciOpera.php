<?php

require_once ('Controller/LoginController.php');
require_once ('Controller/ArtworksController.php');

session_start();

if (!LoginController::isAuthenticatedUser() || !LoginController::isAdminUser()) {
   header('Location: Errore.php');
}

$message = '';
$title = '';

if (isset($_POST['submit']) && $_POST['submit'] === 'Inserisci') {
    if (isset($_POST['author'])) {
        $author = $_POST['author'];
    } else {
        $author = '';
    }

    if (isset($_POST['title'])) {
        $title = $_POST['title'];
    } else {
        $title = '';
    }

    if (isset($_POST['description'])) {
        $description = $_POST['description'];
    } else {
        $description = '';
    }

    if (isset($_POST['years'])) {
        $years = $_POST['years'];
    } else {
        $years = '';
    }

    if (isset($_POST['style'])) {
        $style = $_POST['style'];
    } else {
        $style = '';
    }

    if (isset($_POST['technique']) && $style === 'Dipinto') {
        $technique = $_POST['technique'];
    } else {
        $technique = '';
    }

    if (isset($_POST['material']) && $style === 'Scultura') {
        $material = $_POST['material'];
    } else {
        $material = '';
    }

    if (isset($_POST['dimensions'])) {
        $dimensions = $_POST['dimensions'];
    } else {
        $dimensions = '';
    }

    if (isset($_POST['loan'])) {
        $loan = ($_POST['loan'] === 'Si' ? 1 : 0);
    } else {
        $loan = 0;
    }

    $artworksController = new ArtworksController();
    $message = $artworksController->addArtwork($author, $title, $description, $years, $style, $technique, $material, $dimensions, $loan, $_SESSION['username']);
    unset($artworksController);
}

if ($message === '' || $message === '<p class="success">L\'opera ' . $title . ' è stata inserita correttamente</p>') {
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
    $hide_technique = '';
    $hide_skip_technique = '';
    $hide_material = ' class="hideContent"';
    $hide_skip_material = ' class="hideContent"';
} else {
    $sculture_style = ' selected="selected" ';
    $hide_material = '';
    $hide_skip_material = '';
    $hide_technique = ' class="hideContent"';
    $hide_skip_technique = ' class="hideContent"';
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
$document = str_replace("<span id='hideTechniqueValuePlaceholder'/>", $hide_technique, $document);
$document = str_replace("<span id='materialValuePlaceholder'/>", $material, $document);
$document = str_replace("<span id='hideMaterialValuePlaceholder'/>", $hide_material, $document);
$document = str_replace("<span id='dimensionsValuePlaceholder'/>", $dimensions, $document);
$document = str_replace("<span id='loanYesCheckedPlaceholder'/>", $loan_yes, $document);
$document = str_replace("<span id='loanNoCheckedPlaceholder'/>", $loan_no, $document);

echo $document;

?>