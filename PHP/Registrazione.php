<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Controller/LoginController.php');
require_once ('Controller/UsersController.php');

session_start();

if (LoginController::isAuthenticatedUser()) {
    header('Location: Error.php');
}

$message = '';

if (isset($_POST['submit']) && $_POST['submit'] === 'Registrati') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $sex = $_POST['sex'];
    $date = $_POST['date'];
    $mail = $_POST['mail'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $repeted_password = $_POST['repetePassword'];

    $users_controller = new UsersController();
    $message = $users_controller->addUser($name, $surname, $sex, $date, $mail, $username, $password, $repeted_passoword);
    unset($users_controller);
}

if ($message === '') {
    $name = '';
    $surname = '';
    $sex = 'A';
    $date = '';
    $mail = '';
    $username = '';
    $password = '';
    $repeted_password = '';
}

$male = ' ';
$female = ' ';
$other = ' ';

if ($sex === 'A') {
    $other = ' checked="checked" ';
} else if ($sex === 'M') {
    $male = ' checked="checked" ';
} else if ($sex === 'F') {
    $female = ' checked="checked" ';
}

$document = file_get_contents('../HTML/Registrazione.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);
$document = str_replace("<span id='nameValuePlaceholder'/>", $name, $document);
$document = str_replace("<span id='surnameValuePlaceholder'/>", $surname, $document);
$document = str_replace("<span id='maleCheckedPlaceholder'/>", $male, $document);
$document = str_replace("<span id='femaleCheckedPlaceholder'/>", $female, $document);
$document = str_replace("<span id='otherCheckedPlaceholder'/>", $other, $document);
$document = str_replace("<span id='dateValuePlaceholder'/>", $date, $document);
$document = str_replace("<span id='mailValuePlaceholder'/>", $mail, $document);
$document = str_replace("<span id='usernameValuePlaceholder'/>", $username, $document);
$document = str_replace("<span id='passwordValuePlaceholder'/>", $password, $document);
$document = str_replace("<span id='repetePasswordValuePlaceholder'/>", $repeted_password, $document);

echo $document;

?>
