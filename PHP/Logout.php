<?php

require_once ('Controller/LoginController.php');

session_start();

if (LoginController::isAuthenticatedUser()) {
    unset($_SESSION['username']);
}

header('Location: HomePage.php');

?>
