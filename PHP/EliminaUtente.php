<?php

session_start();

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Rimuovi' || !isset($_SESSION['userPage'])) {
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

$page = $_SESSION['userPage'];

header('Location: GestioneUtenti.php?page=' . $page);

?>
