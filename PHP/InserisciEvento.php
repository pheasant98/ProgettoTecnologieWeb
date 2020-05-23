<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Controller/LoginController.php');
require_once ('Controller/EventsController.php');

session_start();

if (!LoginController::isAuthenticatedUser()) {
  header('Location: Error.php');
}

$message = '';

if (isset($_POST['submit']) && $_POST['submit'] === 'Inserisci') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $beginDate = $_POST['beginDate'];
    $endDate = $_POST['endDate'];
    $type = $_POST['type'];
    $manager = $_POST['manager'];

    $eventsController = new EventsController();
    $message = $eventsController->addEvent($title, $description, $beginDate, $endDate, $type, $manager, $_SESSION['username']);
    unset($eventsController);
}

if ($message === '') {
    $title = '';
    $description = '';
    $beginDate = '';
    $endDate = '';
    $type = '';
    $manager = '';
}

$document = file_get_contents('../HTML/InserisciEvento.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);;
$document = str_replace("<span id='titleValuePlaceholder'/>", $title, $document);
$document = str_replace("<span id='descriptionValuePlaceholder'/>", $description, $document);
$document = str_replace("<span id='beginDateValuePlaceholder'/>", $beginDate, $document);
$document = str_replace("<span id='endDateValuePlaceholder'/>", $endDate, $document);
$document = str_replace("<span id='typeValuePlaceholder'/>", $type, $document); //TODO: Sistemare come per la select delle opere
$document = str_replace("<span id='managerValuePlaceholder'/>", $manager, $document);

echo $document;

?>