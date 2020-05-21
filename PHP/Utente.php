<?php

require_once ('Utilities/DateUtilities.php');
require_once ('Controller/LoginController.php');
require_once ('Controller/UsersController.php');

session_start();

if (!LoginController::isAuthenticatedUser() || !LoginController::isAdminUser() || !isset($_GET['user'])) {
    header('Location: Error.php');
}

$users_controller = new UsersController();
$user = $users_controller->getUser($_GET['user']);
unset($users_controller);

$document = file_get_contents('../HTML/Utente.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='titlePlaceholder'/>", $_GET['user'], $document);
$document = str_replace("<span id='userNamePlaceholder'/>", $user['Nome'], $document);
$document = str_replace("<span id='userSurnamePlaceholder'/>", $user['Cognome'], $document);
$document = str_replace("<span id='userSexPlaceholder'/>", $user['Sesso'] === 'M' ? 'Maschile' : ($user['Sesso'] === 'F' ? 'Femminile' : 'Non specificato'), $document);
$document = str_replace("<span id='userBirthDatePlaceholder'/>", DateUtilities::englishItalianDate($user['DataNascita']), $document);
$document = str_replace("<span id='userMailPlaceholder'/>", $user['Email'], $document);

echo $document;

?>
