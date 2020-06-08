<?php

require_once ('Controller/LoginController.php');
require_once ('Controller/EventsController.php');
require_once ('Utilities/InputCheckUtilities.php');

session_start();

if (!LoginController::isAuthenticatedUser() || !LoginController::isAdminUser()) {
  header('Location: Errore.php');
}

$message = '';
$title = '';

if (isset($_POST['submit']) && $_POST['submit'] === 'Inserisci') {
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
    if (isset($_POST['beginDate'])) {
        $begin_date = $_POST['beginDate'];
    } else {
        $begin_date = '';
    }
    if (isset($_POST['endDate'])) {
        $end_date = $_POST['endDate'];
    } else {
        $end_date = '';
    }
    if (isset($_POST['type'])) {
        $type = $_POST['type'];
    } else {
        $type = '';
    }
    if (isset($_POST['manager'])) {
        $manager = $_POST['manager'];
    } else {
        $manager = '';
    }

    $eventsController = new EventsController();
    $message = $eventsController->addEvent($title, $description, $begin_date, $end_date, $type, $manager, $_SESSION['username']);
    unset($eventsController);
}

if ($message === '' || $message === '<p class="success">L\' evento ' . InputCheckUtilities::prepareStringForDisplay((InputCheckUtilities::prepareStringForChecks($title))) . ' è stato inserito correttamente</p>') {
    $title = '';
    $description = '';
    $begin_date = '';
    $end_date = '';
    $type = 'Mostra';
    $manager = '';
}

$exhibition_type = ' ';
$conference_type = ' ';

if ($type === 'Mostra') {
    $exhibition_type = ' selected="selected" ';
} else {
    $conference_type = ' selected="selected" ';
}

$document = file_get_contents('../HTML/InserisciEvento.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);
$document = str_replace("<span id='titleValuePlaceholder'/>", $title, $document);
$document = str_replace("<span id='descriptionValuePlaceholder'/>", $description, $document);
$document = str_replace("<span id='exhibitionTypeSelectedPlaceholder'/>", $exhibition_type, $document);
$document = str_replace("<span id='conferenceTypeSelectedPlaceholder'/>", $conference_type, $document);
$document = str_replace("<span id='beginDateValuePlaceholder'/>", $begin_date, $document);
$document = str_replace("<span id='endDateValuePlaceholder'/>", $end_date, $document);
$document = str_replace("<span id='managerValuePlaceholder'/>", $manager, $document);

echo $document;

?>