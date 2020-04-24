<?php

require_once("Database/DatabaseAccess.php");
require_once("Controller/LoginController.php");

session_start();

$document = file_get_contents("../HTML/HomePage.html");
$login = LoginController::getAuthenticationMenu(isset($_SESSION['username']));
$document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);

echo $document;

?>
