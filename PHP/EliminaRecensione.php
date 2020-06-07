<?php

session_start();

require_once ('Controller/LoginController.php');

echo "ufffa";
if (!isset($_POST['submit']) || $_POST['submit'] !== 'Rimuovi' || !isset($_SESSION['reviewPage'])) {
    header('Location: Errore.php');
}

require_once ('Controller/ReviewsController.php');

$controller = new ReviewsController();

$review = $controller->getReview($_POST['id']);
if ($controller->deleteReview($_POST['id'])) {
    $_SESSION['reviewDeletedError'] = true;
} else {
    $_SESSION['reviewDeletedError'] = false;
}

unset($controller);

$_SESSION['reviewDeleted'] = $review['Oggetto'];

$review_count = $_SESSION['review_number_count'];
$offset = ($_SESSION['reviewPage'] - 1) * 5;
if ($offset === ($review_count - 1)) {
    $page = $_SESSION['reviewPage'] - 1;
} else {
    $page = $_SESSION['reviewPage'];
}


header('Location: GestioneRecensioni.php?page=' . $page);

?>
