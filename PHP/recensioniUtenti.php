<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once ('Controller/LoginController.php');
require_once ('Controller/ReviewsController.php');

$controller = new ReviewsController();
$reviews_count = $controller->getReviewsCount();

if (!isset($_GET['page'])) {
$page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($reviews_count / 5))) {
header('Location: Error.php');
} else {
$page = $_GET['page'];
}
