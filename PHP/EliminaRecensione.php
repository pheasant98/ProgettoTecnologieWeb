<?php

session_start();

if (!isset($_POST['submit']) || $_POST['submit'] !== 'Rimuovi' || !isset($_SESSION['reviewPage'])) {
    header('Location: Errore.php');
}

require_once ('Controller/ReviewsController.php');

$controller = new ReviewsController();

$review = $controller->getReview($_POST['id']);
$controller->deleteReview($_POST['id']);

unset($controller);

$_SESSION['reviewDeleted'] = $review['Oggetto'];

$page = $_SESSION['reviewPage'];

header('Location: GestioneRecensioni.php?page=' . $page);

?>
