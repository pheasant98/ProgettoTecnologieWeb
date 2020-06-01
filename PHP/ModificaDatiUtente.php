<?php

require_once ('Controller/LoginController.php');
require_once ('Controller/UsersController.php');

session_start();

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}
$message = '';
$usersController = new UsersController();
$user = $usersController->getUser($_SESSION['username']);

$name = $user['Nome'];
$surname = $user['Cognome'];
$sex = $user['Sesso'];
$date = $user['DataNascita'];
$mail = $user['Email'];

if (isset($_POST['submit']) && $_POST['submit'] === 'Modifica') {
    $name = $_POST['Name'];
    $surname = $_POST['Surname'];
    if ($_POST['Sex'] === 'Maschile') {
       $sex = 'M';
    } elseif ($_POST['Sex'] === 'Femminile') {
        $sex = 'F';
    } else {
        $sex = 'A';
    }
    $date = $_POST['Date'];
    $mail = $_POST['Mail'];
    $oldPassword = hash('sha256', $_POST['OldPassword']);
    $newPassword = hash('sha256', $_POST['NewPassword']);
    $repeated_password = hash('sha256', $_POST['RepeatePassword']);
    $message = $usersController->updateUser($_SESSION['username'], $name, $surname, $date, $sex, $mail, $oldPassword, $newPassword, $repeated_password);
    unset($usersController);
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

echo $document;

?>