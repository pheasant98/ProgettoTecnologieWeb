<?php

require_once ('Utilities/DateUtilities.php');
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
$date = DateUtilities::englishItalianDate($user['DataNascita']);
$mail = $user['Email'];

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
    $date = DateUtilities::englishItalianDate($_POST['date']);
    $mail = $_POST['mail'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $repeated_password = $_POST['repetePassword'];
    $message = $usersController->updateUser($_SESSION['username'], $name, $surname, $date, $sex, $mail, $oldPassword, $newPassword, $repeated_password);
    unset($usersController);
    if ($message === '') {
        header('Location: DatiUtenteModificati.php');
    }
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