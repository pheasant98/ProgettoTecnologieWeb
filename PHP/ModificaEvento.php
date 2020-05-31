<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Controller/LoginController.php');
require_once ('Controller/EventsController.php');

session_start();

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$message = '';
$eventsController = new EventsController();
$event = $eventsController->getEvent($_GET['id']);

$title = $event['Titolo'];
$description = $event['Descrizione'];
$type = $event['Tipologia'];
$begin_date = $event['DataInizio'];
$end_date = $event['DataFine'];
$manager = $event['Organizzatore'];

if (isset($_POST['submit']) && $_POST['submit'] === 'Modifica') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $begin_date = $_POST['beginDate'];
    $end_date = $_POST['endDate'];
    $type = $_POST['type'];
    $manager = $_POST['manager'];
    $_SESSION['event_title'] = $_POST['title'];
    $message = $eventsController->updateEvent($_GET['id'], $title, $description, $begin_date, $end_date, $type, $manager, $_SESSION['username']);
    if($message === '') {
        header('Location: EventoModificato.php');
    }
    unset($eventsController);
}

$exhibition_type = ' ';
$conference_type = ' ';

if ($type === 'Mostra') {
    $exhibition_type = ' selected="selected" ';
} else {
    $conference_type = ' selected="selected" ';
}

$document = file_get_contents('../HTML/ModificaEvento.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);;
$document = str_replace("<span id='titleValuePlaceholder'/>", $title, $document);
$document = str_replace("<span id='descriptionValuePlaceholder'/>", $description, $document);
$document = str_replace("<span id='exhibitionTypeSelectedPlaceholder'/>", $exhibition_type, $document);
$document = str_replace("<span id='conferenceTypeSelectedPlaceholder'/>", $conference_type, $document);
$document = str_replace("<span id='beginDateValuePlaceholder'/>", $begin_date, $document);
$document = str_replace("<span id='endDateValuePlaceholder'/>", $end_date, $document);
$document = str_replace("<span id='managerValuePlaceholder'/>", $manager, $document);

echo $document;

?>