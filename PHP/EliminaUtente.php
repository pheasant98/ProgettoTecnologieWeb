<?php

session_start();

require_once ('Controller/LoginController.php');

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Rimuovi' || !isset($_SESSION['userPage']) || !LoginController::isAdminUser()) {
    header('Location: Errore.php');
}

require_once ('Controller/UsersController.php');

$controller = new UsersController();
if ($controller->deleteUser($_POST['username'])) {
    $_SESSION['userDeletedError'] = true;
} else {
    $_SESSION['userDeletedError'] = false;
}
unset($controller);

$_SESSION['userDeleted'] = $_POST['username'];

$user_count = $_SESSION['user_number_count'];
$offset = ($_SESSION['userPage'] - 1) * 5;
if ($offset === ($user_count - 1)) {
    $page = $_SESSION['userPage'] - 1;
} else {
    $page = $_SESSION['userPage'];
}

header('Location: GestioneUtenti.php?page=' . $page);

?>
