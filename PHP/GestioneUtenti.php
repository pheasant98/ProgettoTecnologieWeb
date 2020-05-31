<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ('Controller/UsersController.php');
require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$controller = new UsersController();
$user_count = $controller->getUsersCount();

if($user_count == 1) {
    $user_number_found = '<p> È stato trovato ' . $user_count . ' utente: </p>';
} else {
    $user_number_found = '<p> Sono stati trovati ' . $user_count . ' utenti: </p>';
}

if (!isset($_GET['page'])) {
    $page = 1;
} elseif (($_GET['page'] < 1) || (($_GET['page'] - 1) > ($user_count / 5))) {
    header('Location: Errore.php');
} else {
    $page = $_GET['page'];
}

$_SESSION['page'] = $page;

$offset = ($page - 1) * 5;

$user_list = $controller->getUsers($offset);

unset($controller);

$previous_users = '';
$next_users = '';

if ($page > 1) {
    $previous_users = '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '" title="Utenti precedenti" role="button" aria-label="Torna agli utenti precedenti"> &lt; Precedente</a>';
}

if (($page * 5) < $user_count) {
    $next_users = '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '" title="Utenti successivi" role="button" aria-label="Vai agli utenti successivi"> Successivo &gt;</a>';
}

$document = file_get_contents('../HTML/GestioneUtenti.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='userNumberFoundPlaceholder'/>", $user_number_found, $document);
$document = str_replace("<span id='userListPlaceholder'/>", $user_list, $document);
$document = str_replace("<span id='buttonBackPlaceholder'/>", $previous_users, $document);
$document = str_replace("<span id='buttonNextPlaceholder'/>", $next_users, $document);

echo $document;

?>
