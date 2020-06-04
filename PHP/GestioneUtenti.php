<?php

require_once ('Controller/UsersController.php');
require_once ('Controller/LoginController.php');

session_start();

if (!LoginController::isAuthenticatedUser()) {
    header('Location: Errore.php');
}

$controller = new UsersController();
$user_count = $controller->getUsersCount();

$deleted = '';
if (isset($_SESSION['userDeleted']) && isset($_SESSION['userDeletedError'])) {
    if ($_SESSION['userDeletedError']) {
        $deleted = 'L\'utente ' . $_SESSION['userDeleted'] . ' è stato eliminato correttamente';
    } else {
        $deleted = 'Non è stato possibile eliminare l\'utente ' . $_SESSION['userDeleted'] . ', se l\'errore persiste si prega di segnalarlo al supporto tecnico.';
    }

    unset($_SESSION['userDeleted']);
    unset($_SESSION['userDeletedError']);
}

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

if ($user_count > 0) {
    $_SESSION['userPage'] = $page;

    $offset = ($page - 1) * 5;

    $user_list = '<ul class="list">' . $controller->getUsers($offset) . '</ul>';

    unset($controller);

    $navigation_users_buttons = '<p class="navigation">';

    if ($page > 1) {
        $navigation_users_buttons .= '<a id="buttonBack" class="button" href="?page=' . ($page - 1) . '" title="Utenti precedenti" role="button" aria-label="Torna agli utenti precedenti"> &lt; Precedente</a>';
    }

    if (($page * 5) < $user_count) {
        $navigation_users_buttons .= '<a id="buttonNext" class="button" href="?page=' . ($page + 1) . '" title="Utenti successivi" role="button" aria-label="Vai agli utenti successivi"> Successivo &gt;</a>';
    }

    $navigation_users_buttons .= '</p>';
    $skip_users = '<a href="#buttonBack" class="skipInformation">Salta gli utenti presenti nella pagina</a>';
} else {
    unset($controller);
    $skip_users = '';
    $user_list = '';
    $navigation_users_buttons = '';
}

$document = file_get_contents('../HTML/GestioneUtenti.html');
$login = LoginController::getAuthenticationMenu();

$document = str_replace("<span id='loginMenuPlaceholder'/>", $login, $document);
$document = str_replace("<span id='deletedContent'/>", $deleted, $document);
$document = str_replace("<span id='userNumberFoundPlaceholder'/>", $user_number_found, $document);
$document = str_replace("<span id='skipUsersPlaceholder'/>", $skip_users, $document);
$document = str_replace("<span id='userListPlaceholder'/>", $user_list, $document);
$document = str_replace("<span id='navigationUsersButtonsPlaceholder'/>", $navigation_users_buttons, $document);

echo $document;

?>
