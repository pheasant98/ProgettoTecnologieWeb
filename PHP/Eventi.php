<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("Controller/LoginController.php");
require_once ("Controller/EventsController.php");

session_start();

$document = file_get_contents("../HTML/Eventi.html");
$login = LoginController::getAuthenticationMenu();

$events_controller = new EventsController();
$event_counter = $events_controller->getEventsCount();
$offset = ($_GET["page"] - 1) * 5;
$event_list = "<dl class=\"clickableList\">" . $events_controller->getEvents($offset) . "</dl>";

$document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);
$document = str_replace("<span id=\"eventNumberPlaceholder\"/>", $event_counter["Totale"], $document);
$document = str_replace("<span id=\"eventListPlaceholder\"/>", $event_list, $document);

echo $document;

?>
