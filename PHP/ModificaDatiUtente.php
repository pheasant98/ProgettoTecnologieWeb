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

$usersController = new UsersController();
$message = '';

if (isset($_POST['submit']) && $_POST['submit'] === 'Modifica') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    if ($_POST['sex'] === 'Maschile') {
       $sex = 'M';
    } elseif ($_POST['sex'] === 'Femminile') {
        $sex = 'F';
    } else {
        $sex = 'A';
    }
    $date = $_POST['date'];
    $mail = $_POST['mail'];
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $repeated_password = hash('sha256', $_POST['repeatePassword']);

    $message = $usersController->updateUser($_GET['id'], $name, $surname, $date, $sex, $_SESSION['username'], $mail, $password, $repeated_password);
    unset($usersController);
}

if ($message === '') {
    $user = $usersController->getUser($_GET['user']);

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $sex = $_POST['sex'];
    $date = $_POST['date'];
    $mail = $_POST['mail'];
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    $repeated_password = '';
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

$document = file_get_contents('../HTML/ModificaDatiUtente.html');
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
$document = str_replace("<span id='repeatePasswordValuePlaceholder'/>", $repeated_password, $document);

echo $document;

?>