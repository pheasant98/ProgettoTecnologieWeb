<?php

session_start();

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Rimuovi' || !isset($_SESSION['page'])) {
    header('Location: Errore.php');
}

require_once ('Controller/ReviewsController.php');

$controller = new ReviewsController();
$controller->deleteReview($_POST['id']);
unset($controller);

$_SESSION['deleted'] = $_POST['id'];

$page = $_SESSION['page'];
unset($_SESSION['page']);

header('Location: GestioneRecensioni.php?page=' . $page);

?>
