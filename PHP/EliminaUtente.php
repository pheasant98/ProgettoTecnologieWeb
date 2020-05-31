<?php

session_start();

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Rimuovi' || !isset($_SESSION['page'])) {
    header('Location: Errore.php');
}

require_once ('Controller/UsersController.php');

$controller = new UsersController();
$controller->deleteUser($_POST['username']);
unset($controller);

$_SESSION['deleted'] = $_POST['username'];

$page = $_SESSION['page'];
unset($_SESSION['page']);

header('Location: GestioneUtenti.php?page=' . $page);

?>
