<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Controller/LoginController.php');
require_once ('Controller/UsersController.php');

session_start();

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Error.php');
}

$document = file_get_contents('../HTML/AreaPersonale.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);

echo $document;

?>
