<?php

session_start();

if (!isset($_GET['id'])) {
    header('Location: Errore.php');
}

require_once ('Controller/LoginController.php');
require_once ('Utilities/DateUtilities.php');
$document = file_get_contents('../HTML/Evento.html');

require_once ('Controller/EventsController.php');
$controller = new EventsController();

$login = LoginController::getAuthenticationMenu();

$event = $controller->getEvent($_GET['id']);

unset($controller);

$event_title = $event['Titolo'];
$event_type = $event['Tipologia'];
$event_begin_date = DateUtilities::englishItalianDate($event['DataInizio']);
$event_end_date = DateUtilities::englishItalianDate($event['DataFine']);
$event_manager = $event['Organizzatore'];
$event_description = $event['Descrizione'];

$document = str_replace("<span id='titlePlaceholder'/>", $event_title, $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='eventTypePlaceholder'/>", $event_type, $document);
$document = str_replace("<span id='eventBeginDatePlaceholder'/>", $event_begin_date, $document);
$document = str_replace("<span id='eventEndDatePlaceholder'/>", $event_end_date, $document);
$document = str_replace("<span id='eventManagerPlaceholder'/>", $event_manager, $document);
$document = str_replace("<span id='eventDescriptionPlaceholder'/>", $event_description, $document);

echo $document;

?>
