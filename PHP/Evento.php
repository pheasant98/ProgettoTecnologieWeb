<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once ('Controller/LoginController.php');
$document = file_get_contents('../HTML/Evento.html');

require_once ('Controller/EventsController.php');
$controller = new EventsController();

$login = LoginController::getAuthenticationMenu();

$single_content = $controller->getEvent($_GET['id']);

$document = str_replace("<span id='titlePlaceholder'/>", "ciao", $document);
$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='singleContentPlaceholder'/>", $single_content, $document);

echo $document;

?>
