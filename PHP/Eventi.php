<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("Controller/LoginController.php");
require_once ("Controller/EventsController.php");

session_start();

$events_controller = new EventsController();
$event_counter = $events_controller->getEventsCount();

if (!isset($_GET["page"]) || $_GET["page"] < 1 || (($_GET["page"] - 1) > ($event_counter["Totale"] / 5))) {
    header('Location: Error.php');
}

$document = file_get_contents("../HTML/Eventi.html");
$login = LoginController::getAuthenticationMenu();

$offset = ($_GET["page"] - 1) * 5;
$event_list = "<dl class=\"clickableList\">" . $events_controller->getEvents($offset) . "</dl>";
$back_events = "";
if ($_GET["page"] > 1) {
    $back_events = "Eventi.php?page=" . ($_GET["page"] - 1);
}
$next_events = "Eventi.php?page=" . ($_GET["page"] + 1);
if (($_GET["page"] * 5) >= $event_counter) {
    $next_events = "";
}

$document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);
$document = str_replace("<span id=\"eventNumberPlaceholder\"/>", $event_counter["Totale"], $document);
$document = str_replace("<span id=\"eventListPlaceholder\"/>", $event_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $back_events, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_events, $document);

echo $document;

?>
