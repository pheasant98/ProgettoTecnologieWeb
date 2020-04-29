<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("Controller/LoginController.php");
require_once ("Controller/EventsController.php");

session_start();

$events_controller = new EventsController();
$events_count = $events_controller->getEventsCount();
$filter_type = "";
$link_filter_type = "";

if (isset($_GET["filterType"])) {
    $link_filter_type = "&amp;filterType=" . $_GET["filterType"];
    if ($_GET["filterType"] == "Mostre") {
        $filter_type ="Mostra";
        $events_count = $events_controller->getEventsCountByType($filter_type);
    } elseif ($_GET["filterType"] == "Conferenze") {
        $filter_type ="Conferenza";
        $events_count = $events_controller->getEventsCountByType($filter_type);
    }
}

if (!isset($_GET["page"])) {
    $page = 1;
} elseif (($_GET["page"] < 1) || (($_GET["page"] - 1) > ($events_count / 5))) {
    header('Location: Error.php');
} else {
    $page = $_GET["page"];
}
$offset = ($page - 1) * 5;

$document = file_get_contents("../HTML/Eventi.html");
$login = LoginController::getAuthenticationMenu();

$event_list = "<dl class=\"clickableList\">" . $events_controller->getEvents($filter_type, $offset) . "</dl>";
$back_events = "";
if ($page > 1) {
    $back_events = "<a id=\"buttonBack\" class=\"button\" href=\"?page=" . ($page - 1) . $link_filter_type . "\" title=\"Eventi precedenti\" role=\"button\" aria-label=\"Torna agli eventi precedenti\"> &lt; Precedente</a>";
}

$next_events = "";
if (($page * 5) < $events_count) {
    $next_events = "<a id=\"buttonNext\" class=\"button\" href=\"?page=" . ($page + 1) . $link_filter_type . "\" title=\"Eventi successivi\" role=\"button\" aria-label=\"Vai agli eventi successive\"> Successivo &gt;</a>";
}

$document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);
$document = str_replace("<span id=\"eventNumberPlaceholder\"/>", $events_count, $document);
$document = str_replace("<span id=\"eventListPlaceholder\"/>", $event_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $back_events, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_events, $document);

echo $document;

?>
