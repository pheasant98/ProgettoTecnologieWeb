<?php

require_once ('Controller/LoginController.php');

session_start();

if (LoginController::isAuthenticatedUser()) {
    unset($_SESSION['username']);
    unset($_SESSION['admin']);
}

header('Location: HomePage.php');

?>
