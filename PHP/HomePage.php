<?php

require_once("Database/DatabaseAccess.php");
require_once("Utilities/Utilities.php");

session_start();

$document = file_get_contents("../HTML/HomePage.html");
$login = getAuthenticationMenu(isset($_SESSION['username']));
$document = str_replace("<span id=\"loginMenuPlaceholder\"/>", $login, $document);

echo $document;

?>
