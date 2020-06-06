<?php

require_once ('Controller/LoginController.php');
require_once ('Controller/UsersController.php');

session_start();

if (LoginController::isAuthenticatedUser()) {
    header('Location: AreaPersonale.php');
}

$message = '';

if (isset($_POST['submit']) && $_POST['submit'] === 'Accedi') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $usersController = new UsersController();
    $user = $usersController->getUserByCredential($username, $password);
    unset($usersController);

    if ($user === null) {
        $message = '<p><span xml:lang="en">Username</span> e/o <span xml:lang="en">password</span> errati!</p>';
    } else {
        $_SESSION['username'] = $user['Username'];
        $_SESSION['admin'] = $user['Admin'];
        header('Location: AreaPersonale.php');
    }
}

if ($message === '') {
    $username = '';
}

$document = file_get_contents('../HTML/Login.html');

$document = str_replace("<span id='outputMessagePlaceholder'/>", $message, $document);
$document = str_replace("<span id='usernameValuePlaceholder'/>", $username, $document);

echo $document;

?>
