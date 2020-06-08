<?php

require_once ('Controller/LoginController.php');
require_once ('Utilities/InputCheckUtilities.php');

session_start();

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$document = file_get_contents('../HTML/DatiUtenteModificati.html');
$login = LoginController::getAuthenticationMenu();
$username = $_SESSION['username'];

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='usernamePlaceholder'/>", InputCheckUtilities::prepareStringForDisplay($username), $document);

echo $document;

?>
